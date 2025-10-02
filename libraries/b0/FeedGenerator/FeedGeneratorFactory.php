<?php
defined('_JEXEC') or die();

JImport('b0.FeedGenerator.FeedDefinitions');

class FeedGeneratorFactory
{
    public function getFeedDefinitions(): array
    {
        return FeedDefinitions::FEEDS;
    }

    /**
     * Гарантирует существование директории для файла
     */
    public function ensureDirectory(string $filePath): void
    {
        $fullPath = JPATH_ROOT . $filePath;
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            jimport('joomla.filesystem.folder');
            JFolder::create($dir);
        }
    }
}
