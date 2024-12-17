<?php
defined('_JEXEC') or die();

JImport('b0.Page.PageKeys');

class Pages
{
    public array $pages = [];

    public function __construct($items, $params)
    {
        foreach ($items as $item) {
            $this->pages[$item->id] = $this->createPage($item);
        }
    }

    private function createPage($item): stdClass
    {
        $page = new stdClass();
        $page->id = $item->id;
        $page->url = $item->url;
        $page->title = $item->title;
        $page->controls = $item->controls;
        $page->body = $item->fields_by_key[PageKeys::KEY_BODY]->result ?? '';
        return $page;
    }
}
