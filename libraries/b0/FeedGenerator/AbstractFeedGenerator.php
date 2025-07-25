<?php
defined('_JEXEC') or die();

abstract class AbstractFeedGenerator implements FeedGeneratorInterface
{
    protected $config;
    protected $items = [];
    protected $objects = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function generate()
    {
        $this->getItems();
        $this->createObjects();
        $this->writeToFile($this->config['filePath']);
    }

    abstract public function getItems();
    abstract public function create();
    abstract public function render();
}