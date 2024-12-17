<?php
defined('_JEXEC') or die;

JImport('b0.Vacancy.VacancyKeys');
JImport('b0.Core.Meta');

class Vacancy extends Item
{
	// Fields
	public $body;
	public $moduleId;
	public $buttonText;

	public function __construct($item, $user)
	{
		parent::__construct($item, $user);
		$fields = $item->fields_by_key;
		$this->metaTitle = $item->title;
		$this->metaDescription = $item->title;
		$this->metaKey = '';

		$this->body = $fields[VacancyKeys::KEY_BODY]->result ?? '';
		$this->moduleId = $fields[VacancyKeys::KEY_MODULE_ID]->result ?? '';
		$this->buttonText = $fields[VacancyKeys::KEY_BUTTON_TEXT]->result ?? '';
	}

	public function renderBody() {
		if (!$this->body) {
			return;
		}
		echo $this->body;
	}
}

