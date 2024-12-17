<?php
defined('_JEXEC') or die();

JImport('b0.Item.Item');
JImport('b0.Page.PageKeys');
JImport('b0.Core.Meta');

class Page extends Item
{
    use Meta;

    public string $body;

    public function __construct($item, $user)
    {
        parent::__construct($item, $user);
        $this->metaTitle = $this->getItemMetaTitle($item);
        $this->metaDescription = $this->getItemMetaDescription($item);
        $this->metaKey = '';
        $this->setBody($item);
    }

    private function getItemMetaTitle(object $item): string
    {
        return $item->meta_key !== '' ? $item->meta_key : $item->title;
    }

    private function getItemMetaDescription(object $item): string
    {
        return $item->meta_descr;
    }

    private function setBody(object $item)
    {
        $this->body = $item->fields_by_key[PostKeys::KEY_BODY]->result ?? '';
    }
}
