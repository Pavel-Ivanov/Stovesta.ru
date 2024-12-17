<?php
defined('_JEXEC') or die;

JImport('b0.Item.Item');
JImport('b0.Post.PostKeys');
JImport('b0.Core.Represent');
JImport('b0.Core.OpenGraph');
JImport('b0.Core.Meta');

class Post extends Item implements RepresentKeys
{
	use Represent, OpenGraph, Meta;

	// Fields
	public string $announcement;
	public string $body;

	public function __construct($item, $user)
	{
		parent::__construct($item, $user);
		$fields = $item->fields_by_key;
		$this->metaTitle = $this->setMetaTitle($item);
		$this->metaDescription = $this->setMetaDescription($item);
		$this->metaKey = '';

		$this->announcement = $fields[PostKeys::KEY_ANNOUNCEMENT]->result ?? '';
		$this->body = $fields[PostKeys::KEY_BODY]->result ?? '';
		$this->setRepresent($fields);
		$this->setOpenGraph($this);
	}
	
	private function setMetaTitle(object $item): string
	{
		return $item->meta_key !== '' ? $item->meta_key : $item->title;
	}
	
	private function setMetaDescription(object $item): string
	{
		return $item->meta_descr;
	}

	public function renderBody() {
		if (!$this->body) {
			return;
		}
		echo $this->body;
	}
}

