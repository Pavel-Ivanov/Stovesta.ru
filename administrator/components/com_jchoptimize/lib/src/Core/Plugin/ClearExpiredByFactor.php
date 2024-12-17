<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads.
 *
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2022 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 *  If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Core\Plugin;

use _JchOptimizeVendor\Laminas\Cache\Storage\Plugin\AbstractPlugin;
use _JchOptimizeVendor\Laminas\Cache\Storage\PostEvent;
use _JchOptimizeVendor\Laminas\Cache\Storage\StorageInterface;
use _JchOptimizeVendor\Laminas\Cache\Storage\TaggableInterface;
use _JchOptimizeVendor\Laminas\EventManager\EventManagerInterface;
use JchOptimize\Core\PageCache\PageCache;
use JchOptimize\Platform\Cache;
use JchOptimize\Platform\Paths;
use JchOptimize\Platform\Profiler;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Filesystem\File;
use Joomla\Registry\Registry;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use function time;

\defined('_JCH_EXEC') or exit('Restricted access');
class ClearExpiredByFactor extends AbstractPlugin implements ContainerAwareInterface, LoggerAwareInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $callback = [$this, 'clearExpiredByFactor'];
        $this->listeners[] = $events->attach('setItem.post', $callback, $priority);
        $this->listeners[] = $events->attach('setItems.post', $callback, $priority);
    }

    /**
     * @throws \Exception
     */
    public function clearExpiredByFactor(PostEvent $event)
    {
        $factor = $this->getOptions()->getClearingFactor();
        if ($factor && 1 === \random_int(1, $factor)) {
            $this->clearExpired();
        }
    }

    private function clearExpired()
    {
        \JCH_DEBUG ? Profiler::start('ClearExpired') : null;

        /** @var Registry $params */
        $params = $this->container->get('params');

        /** @var TaggableInterface $taggableCache */
        $taggableCache = $this->container->get(TaggableInterface::class);

        /** @var StorageInterface $cache */
        $cache = $this->container->get(StorageInterface::class);

        /** @var PageCache $pageCache */
        $pageCache = $this->container->get(PageCache::class);
        $ttl = $cache->getOptions()->getTtl();
        $ttlPageCache = $pageCache->getStorage()->getOptions()->getTtl();
        $time = \time();
        // Temporarily set taggable cache ttl to 0
        $existingTtl = $taggableCache->getOptions()->getTtl();
        $taggableCache->getOptions()->setTtl(0);
        $pageUrlsToDelete = [];
        $pageUrlsDeleted = [];
        foreach ($taggableCache->getIterator() as $item) {
            $tags = $taggableCache->getTags($item);
            $metaData = $taggableCache->getMetadata($item);
            $mtime = $metaData['mtime'];
            $deleteTag = \true;
            // If item was only used on the page once more than 5 minutes ago it's safe to delete
            // Or if this is not a page cache, and it's expired delete here
            if (\is_array($tags) && 1 === \count($tags) && $time > $mtime + 360 || isset($tags[0]) && 'pagecache' != $tags[0] && $time >= $mtime + $ttl) {
                try {
                    // Remove cache
                    $cache->removeItem($item);
                    // Record urls cache used on if not already deleted
                    $existingUrls = \array_diff($tags, $pageUrlsDeleted);
                    $pageUrlsToDelete = \array_unique(\array_merge($pageUrlsToDelete, $existingUrls));
                } catch (\Throwable $e) {
                    // Don't bother to remove tags if this didn't work, we'll try again next time
                    $deleteTag = \false;
                }
                // We need to also delete the static css/js file if that option is set
                if ('2' == $params->get('htaccess', '2')) {
                    $files = [Paths::cachePath(\false).'/css/'.$item.'.css', Paths::cachePath(\false).'/js/'.$item.'.js'];

                    try {
                        foreach ($files as $file) {
                            if (\file_exists($file)) {
                                File::delete($file);
                                // If for some reason the file still exists don't delete tags
                                if (\file_exists($file)) {
                                    $deleteTag = \false;
                                }

                                break;
                            }
                        }
                    } catch (\Throwable $e) {
                        // Don't bother to delete the tags if this didn't work
                        $deleteTag = \false;
                    }
                }
                // Remove tag if cache successfully deleted
                try {
                    if ($deleteTag && $cache !== $taggableCache) {
                        $taggableCache->removeItem($item);
                    }
                } catch (\Throwable $e) {
                    // Just ignore, we'll get another chance if this didn't work this time.
                }
            }
            // If page cache just delete if expired
            // Or if this ran while caching enabled delete current page
            if (isset($tags[0]) && 'pagecache' == $tags[0] && ($time >= $mtime + $ttlPageCache || \in_array($tags[1], $pageUrlsToDelete))) {
                // Remove cache and tags
                $pageCache->deleteItemById($item);
                // Record url deleted
                $pageUrlsDeleted[] = $tags[1];
                // Remove item from pageURlsToDelete array
                $key = \array_search($tags[1], $pageUrlsToDelete);
                if (\false !== $key) {
                    unset($pageUrlsToDelete[$key]);
                }
            }
        }
        // If we still have urls not yer deleted we do so now
        if (!empty($pageUrlsToDelete)) {
            $pageCache->deleteItemsByUrl($pageUrlsToDelete);
        }
        $taggableCache->getOptions()->setTtl($existingTtl);
        // Finally attempt to clean any third party page cache
        Cache::cleanThirdPartyPageCache();
        \JCH_DEBUG ? Profiler::stop('ClearExpired', \true) : null;
    }
}
