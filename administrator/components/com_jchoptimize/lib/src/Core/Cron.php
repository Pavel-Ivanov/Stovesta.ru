<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/core
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2022 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 *  If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */
namespace JchOptimize\Core;

\defined('_JCH_EXEC') or die('Restricted access');
use JchOptimize\Container;
use JchOptimize\Platform\Paths;
use JchOptimize\Platform\Profiler;
use Joomla\Filesystem\File;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use _JchOptimizeVendor\Laminas\Cache\Exception\ExceptionInterface;
use _JchOptimizeVendor\Laminas\Cache\Storage\Adapter\Filesystem;
use _JchOptimizeVendor\Laminas\Cache\Storage\IterableInterface;
use _JchOptimizeVendor\Laminas\Cache\Storage\StorageInterface;
use _JchOptimizeVendor\Laminas\Cache\Storage\TaggableInterface;
use Serializable;
use function file_exists;
class Cron implements Serializable
{
    use \JchOptimize\Core\SerializableTrait;
    /**
     * @var StorageInterface
     */
    private $cache;
    /**
     * @var Filesystem
     */
    private $fsCache;
    /**
     * @var Registry
     */
    private $params;
    /**
     * @var Input
     */
    private $input;
    /**
     * @param   Registry  $params
     * @param   Input     $input
     */
    public function __construct(Registry $params, Input $input)
    {
        $this->params = $params;
        $this->input = $input;
        //Create new instance of container to get cache. We want to set Ttl without affecting the rest of application
        $container = Container::getNewInstance();
        $this->cache = $container->get(StorageInterface::class);
        $this->fsCache = $container->get(Filesystem::class);
        $this->cache->getOptions()->setTtl(0);
        $this->fsCache->getOptions()->setTtl(0);
    }
    /**
     * @param   string  $currentUrl  Url of current page passed to ensure we're generating a different CRON cache for each page
     */
    public function runCronTasks(string $currentUrl) : string
    {
        //Don't allow users exempt from page caching to delete the cache
        if (empty($this->input->cookie->get('jch_optimize_no_cache'))) {
            $this->garbageCron($currentUrl);
        }
        return 'CRON COMPLETED';
    }
    /**
     * @param   string  $currentUrl
     */
    public function garbageCron(string $currentUrl)
    {
        JCH_DEBUG ? Profiler::start('GarbageCron') : null;
        try {
            if ($this->cache instanceof IterableInterface && $this->cache instanceof TaggableInterface) {
                $iterableTaggableCache = $this->cache;
            } else {
                $iterableTaggableCache = $this->fsCache;
            }
        } catch (\Exception $e) {
            //Alright forget it. This isn't going to work
            return;
        }
        foreach ($iterableTaggableCache->getIterator() as $item) {
            //If only the current url is set in tag then we need to delete the item since it is only used
            //on this page. This ensures we're deleting items that were generated on each page load without
            //deleting items that may be still cached in the HTML of another page
            $tags = $iterableTaggableCache->getTags($item);
            if ([$currentUrl] === $tags) {
                try {
                    $this->cache->removeItem($item);
                    if ($this->cache !== $iterableTaggableCache) {
                        $this->fsCache->removeItem($item);
                    }
                    //We need to also delete the static css/js file if that option is set
                    if ($this->params->get('htaccess', '2') == '2') {
                        $cssUrl = Paths::cachePath(\false) . '/css/' . $item . '.css';
                        $jsUrl = Paths::cachePath(\false) . '/js/' . $item . '.js';
                        try {
                            if (file_exists($cssUrl)) {
                                File::delete($cssUrl);
                            }
                            if (file_exists($jsUrl)) {
                                File::delete($jsUrl);
                            }
                        } catch (\Exception $e) {
                            //Well, we tried.
                        }
                    }
                } catch (ExceptionInterface $e) {
                }
            }
        }
        JCH_DEBUG ? Profiler::stop('GarbageCron', \true) : null;
    }
}
