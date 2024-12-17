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

namespace JchOptimize\Core;

use _JchOptimizeVendor\Laminas\EventManager\SharedEventManager;
use _JchOptimizeVendor\Laminas\EventManager\SharedEventManagerInterface;
use JchOptimize\Container as JchOptimizeContainer;
use JchOptimize\Core\Service\CachingConfigurationProvider;
use JchOptimize\Core\Service\CachingProvider;
use JchOptimize\Core\Service\CallbackProvider;
use JchOptimize\Core\Service\CoreProvider;
use JchOptimize\Core\Service\FeatureHelpersProvider;
use JchOptimize\Core\Service\IlluminateViewFactoryProvider;
use JchOptimize\Core\Service\SpatieProvider;
use Joomla\DI\Container as JoomlaContainer;

\defined('_JCH_EXEC') or exit('Restricted access');

/**
 * A class to easily fetch a Joomla\DI\Container with all dependencies registered.
 * To be extended by JchOptimize\Container.
 */
abstract class AbstractContainer
{
    protected static ?JoomlaContainer $instance = null;

    /**
     * Used to create a new global instance of Joomla/DI/Container or in cases where the container isn't
     * accessible by dependency injection.
     */
    public static function getInstance(): JoomlaContainer
    {
        if (\is_null(self::$instance)) {
            self::$instance = self::getNewInstance();
        }

        return self::$instance;
    }

    /**
     * Used to return a new instance of the Container when we're making changes we don't want to affect the
     * global container.
     *
     * @return JoomlaContainer;
     */
    public static function getNewInstance(): JoomlaContainer
    {
        $jchOptimizeContainer = new JchOptimizeContainer();
        $joomlaContainer = new JoomlaContainer();
        $jchOptimizeContainer->registerCoreProviders($joomlaContainer);
        $jchOptimizeContainer->registerPlatformProviders($joomlaContainer);

        return $joomlaContainer;
    }

    protected function registerCoreProviders(JoomlaContainer $container): void
    {
        $container->alias(SharedEventManager::class, SharedEventManagerInterface::class)->share(SharedEventManagerInterface::class, new SharedEventManager(), \true)->registerServiceProvider(new CoreProvider())->registerServiceProvider(new CachingConfigurationProvider())->registerServiceProvider(new CallbackProvider())->registerServiceProvider(new CachingProvider())->registerServiceProvider(new IlluminateViewFactoryProvider());
        if (\JCH_PRO) {
            $container->registerServiceProvider(new FeatureHelpersProvider())->registerServiceProvider(new SpatieProvider());
        }
    }

    /**
     * To be implemented by JchOptimize/Container to attach service providers specific to the particular platform.
     */
    abstract protected function registerPlatformProviders(JoomlaContainer $container): void;
}
