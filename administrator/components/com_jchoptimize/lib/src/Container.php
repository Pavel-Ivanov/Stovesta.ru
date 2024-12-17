<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads.
 *
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2021 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize;

use JchOptimize\Service\ConfigurationProvider;
use JchOptimize\Service\DatabaseProvider;
use JchOptimize\Service\LoggerProvider;
use JchOptimize\Service\MvcProvider;
use JchOptimize\Service\ReCacheProvider;
use Joomla\DI\Container as JoomlaContainer;

\defined('_JEXEC') or exit('Restricted access');

/**
 * A class to easily fetch a Joomla\DI\Container with all dependencies registered.
 */
class Container extends \JchOptimize\Core\AbstractContainer
{
    protected function registerPlatformProviders(JoomlaContainer $container): void
    {
        $container->registerServiceProvider(new DatabaseProvider())->registerServiceProvider(new ConfigurationProvider())->registerServiceProvider(new LoggerProvider())->registerServiceProvider(new MvcProvider());
        if (\JCH_PRO) {
            $container->registerServiceProvider(new ReCacheProvider());
        }
    }
}
