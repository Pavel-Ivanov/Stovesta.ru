<?php
defined('_JEXEC') or die();

JImport('b0.FeedGenerator.FeedGeneratorConfig');
JImport('b0.FeedGenerator.FeedGeneratorFactory');
use Exception;

class FeedGenerationManager
{
    private $feedConfigs;
    private $feedGeneratorFactory;

    public function __construct(FeedGeneratorFactory $feedGeneratorFactory)
    {
        $this->feedConfigs = FeedGeneratorConfig::FEED_GENERATOR_FEEDS;
        $this->feedGeneratorFactory = $feedGeneratorFactory;
    }

    public function generateAllFeeds(): array
    {
        $results = [];
        foreach ($this->feedConfigs as $feedName => $feedConfig) {
            if ($feedConfig['isNeed']) {
                $results[$feedName] = $this->generateSingleFeed($feedName);
            }
        }
        return $results;
    }

    public function generateSingleFeed(string $feedName): array
    {
        try {
            if (!isset($this->feedConfigs[$feedName])) {
                throw new Exception("Feed configuration for '$feedName' not found");
            }

            $feedGenerator = $this->feedGeneratorFactory->createFeedGenerator($feedName);
            $feedGenerator->generate();

            return [
                'success' => true,
                'message' => "Feed '$feedName' generated successfully"
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}