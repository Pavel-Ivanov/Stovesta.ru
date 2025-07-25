<?php
defined('_JEXEC') or die();

class FeedGeneratorFactory
{
    public function createFeedGenerator($feedName)
    {
        $feedConfig = $this->getFeedConfig($feedName);
        $className = $feedConfig['type'] . 'FeedGenerator';

        if (!class_exists($className)) {
            throw new Exception("Class $className does not exist");
        }

        return new $className($feedConfig);
    }

    private function getFeedConfig($feedName)
    {
        // Логика определения конфигурации фида на основе имени
        // Например:
        $allConfigs = FeedGeneratorConfig::getAllConfigs();
        if (!isset($allConfigs[$feedName])) {
            throw new Exception("Feed configuration for '$feedName' not found");
        }
        return $allConfigs[$feedName];
    }
}