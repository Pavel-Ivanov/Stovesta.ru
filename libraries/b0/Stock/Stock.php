<?php
defined('_JEXEC') or die;

JImport('b0.Stock.StockKeys');
JImport('b0.Core.Represent');
JImport('b0.Core.RepresentKeys');
//JImport('b0.Core.OpenGraph');
JImport('b0.Core.Meta');

class Stock extends Item implements RepresentKeys
{
	use Represent;

	// Fields
	public $body;
	public $moduleId;

	public function __construct($item, $user)
	{
		parent::__construct($item, $user);
		$fields = $item->fields_by_key;
		$this->metaTitle = $item->title;
		$this->metaDescription = $item->title;
		$this->metaKey = '';

		$this->body = $fields[StockKeys::KEY_BODY]->result ?? '';
		$this->moduleId = $fields[StockKeys::KEY_MODULE_ID]->result ?? '';
		$this->setImage($fields);
//		$this->setOpenGraph($this);
	}

	public function renderBody() {
		if (!$this->body) {
			return;
		}
		echo $this->body;
	}
}

