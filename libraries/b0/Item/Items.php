<?php
defined('_JEXEC') or die();

class Items
{
	public array $items = [];
	protected $paramsList = null;
	
	public function __construct($items, $paramsList = null)
	{
		$this->paramsList = $paramsList;
	}
}