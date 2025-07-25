<?php
defined('_JEXEC') or die();

interface FeedGeneratorInterface
{
    public function generate();

    public function getItems();

    public function create();

    public function render();

}