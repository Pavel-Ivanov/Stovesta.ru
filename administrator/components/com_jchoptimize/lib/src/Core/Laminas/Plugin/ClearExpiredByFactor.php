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

namespace JchOptimize\Core\Laminas\Plugin;

use _JchOptimizeVendor\Joomla\DI\ContainerAwareInterface;
use _JchOptimizeVendor\Joomla\DI\ContainerAwareTrait;
use _JchOptimizeVendor\Laminas\Cache\Storage\Plugin\AbstractPlugin;
use _JchOptimizeVendor\Laminas\Cache\Storage\PostEvent;
use _JchOptimizeVendor\Laminas\Cache\Storage\StorageInterface;
use _JchOptimizeVendor\Laminas\Cache\Storage\TaggableInterface;
use _JchOptimizeVendor\Laminas\EventManager\EventManagerInterface;
use JchOptimize\Core\PageCache\PageCache;
use JchOptimize\Platform\Cache;
use JchOptimize\Platform\Paths;
use JchOptimize\Platform\Profiler;
use Joomla\Filesystem\File;
use Joomla\Registry\Registry;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

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
        !\JCH_DEBUG ?: Profiler::start('ClearExpired');

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
        // Let's build an array of items to delete
        $itemsToDelete = [];
        $pageItems = [];
        foreach ($taggableCache->getIterator() as $item) {
            $tags = $taggableCache->getTags($item);
            $metaData = $taggableCache->getMetadata($item);
            $mtime = $metaData['mtime'];
            // Handle items that are not page cache
            if (\is_array($tags) && isset($tags[0]) && 'pagecache' != $tags[0]) {
                // If item was only used on the page once more than 5 minutes ago it's safe to delete
                // Or if it's expired delete here
                if ($params->get('delete_expiry_mode', '0') && 1 === \count($tags) && $time > $mtime + 360 || $time >= $mtime + $ttl) {
                    // Add each tag as index of array and attach cache item
                    foreach ($tags as $tag) {
                        $itemsToDelete[$tag]['items_on_page'][] = $item;
                    }
                }
            }
            // Record each page item for now with their mtime
            if (\is_array($tags) && isset($tags[0]) && 'pagecache' == $tags[0]) {
                $pageItems[$tags[1]] = ['mtime' => $mtime, 'id' => $item];
            }
        }
        // Collate page cache items
        foreach ($pageItems as $url => $pageItem) {
            if (isset($itemsToDelete[$url]) || $time >= $pageItem['mtime'] + $ttlPageCache) {
                $itemsToDelete[$url]['page_cache_id'][] = $pageItem['id'];
            }
        }
        // Collect items that were on a page that wasn't deleted successfully
        $dontDeleteItems = [];
        // Delete page cache
        foreach ($itemsToDelete as $url => $itemsStack) {
            // If page cache exists and wasn't successfully deleted, don't delete items on page
            if (isset($itemsStack['page_cache_id'])) {
                foreach ($itemsStack['page_cache_id'] as $pageCacheId) {
                    if (!$pageCache->deleteItemById($pageCacheId)) {
                        $dontDeleteItems = \array_merge($dontDeleteItems, $itemsStack['items_on_page']);
                        unset($itemsToDelete[$url]);
                    }
                }
            }
            $itemsOnPages = \array_unique(\array_reduce(\array_column($itemsToDelete, 'items_on_page'), 'array_merge', []));
            // If there are items to be deleted we disable page caching so the page isn't cached
            // after the items are deleted
            if (!empty($itemsOnPages)) {
                $pageCache->disableCaching();
            }
            // Delete items on page
            foreach ($itemsOnPages as $key => $itemOnPage) {
                if (\in_array($itemOnPage, $dontDeleteItems)) {
                    unset($itemsOnPages[$key]);

                    continue;
                }
                $cache->removeItem($itemOnPage);
                $deleteTag = !$cache->hasItem($itemOnPage);
                // We need to also delete the static css/js file if that option is set
                if ('2' == $params->get('htaccess', '2')) {
                    $files = [Paths::cachePath(\false).'/css/'.$itemOnPage.'.css', Paths::cachePath(\false).'/js/'.$itemOnPage.'.js'];

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
                if ($deleteTag) {
                    $taggableCache->removeItem($itemOnPage);
                }
            }
            if (!empty($itemsOnPages)) {
                // Finally attempt to clean any third party page cache
                Cache::cleanThirdPartyPageCache();
            }
            !\JCH_DEBUG ?: Profiler::stop('ClearExpired', \true);
        }
    }
}
