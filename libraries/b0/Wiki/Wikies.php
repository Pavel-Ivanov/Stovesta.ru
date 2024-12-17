<?php
defined('_JEXEC') or die();

JImport('b0.Item.Items');
JImport('b0.Wiki.WikiIds');
JImport('b0.Company.CompanyConfig');
JImport('b0.pricehelper');
use Joomla\CMS\User\User;

class Wikies extends Items
{
	public function __construct($items, $paramsList = null)
	{
		parent::__construct($items, $paramsList);
		
		foreach ($items as $item) {
			$article = new stdClass();
			$article->id = $item->id;
			$article->url = JRoute::_($item->url);
			$article->title = $item->title;
			$article->controls = $item->controls;
			$article->image = $this->setImage($item);
			$article->announcement = $item->fields_by_id[WikiIds::ID_ANNOUNCEMENT]->result ?? '';
			$this->items[$item->id] = $article;
		}
	}
	
	private function setImage($item)
	{
		return $item->fields_by_id[WikiIds::ID_IMAGE]->result ?? '<img src="'.$this->paramsList->get('tmpl_core.default_picture').
			'" alt="' . $item->title . '" title="' . $item->title . '">';
	}
}
