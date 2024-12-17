<?php
defined('_JEXEC') or die();
JImport('b0.Item.Item');
JImport('b0.Kit.KitIds');
JImport('b0.Kit.KitKeys');
JImport('b0.fixtures');

class Kit extends Item
{
	public $sparepartsList = [];
	public $accessoriesList = [];
	public $worksList = [];
	public $goodsList = [];

	public function __construct($item, $user = null)
	{
		parent::__construct($item, $user);
		//*** Поля
		$fields = $item->fields_by_key;
		//*** Состав
		$this->sparepartsList = $this->setSparepartsList($fields);
		$this->accessoriesList = $this->setAccessoriesList($fields);
		$this->worksList = $this->setWorksList($fields);
		$this->goodsList = $this->setGoodsList();
	}

	private function setSparepartsList($fields): array
	{
		if (!isset($fields[KitKeys::KEY_SPAREPARTS_LIST]->content['list'])) {
			return [];
		}
		$arr = [];
		foreach ($fields[KitKeys::KEY_SPAREPARTS_LIST]->content['list'] as $sparepart)
		{
			$fieldsByKey = $sparepart->fields_by_key;
			$arr[$sparepart->id] = [
				'title' => $sparepart->title,
				'url' => JRoute::_($sparepart->url),
			];
		}
		return $arr;
	}

	private function setAccessoriesList($fields): array
	{
		if (!isset($fields[KitKeys::KEY_ACCESSORIES_LIST]->content['list'])) {
			return [];
		}
		$arr = [];
		foreach ($fields[KitKeys::KEY_ACCESSORIES_LIST]->content['list'] as $accessory)
		{
			$fieldsByKey = $accessory->fields_by_key;
			$arr[$accessory->id] = [
				'title' => $accessory->title,
				'url' => JRoute::_($accessory->url),
			];
		}
		return $arr;
	}

	private function setWorksList($fields): array
	{
		if (!isset($fields[KitKeys::KEY_WORKS_LIST]->content['list'])) {
			return [];
		}
		$arr = [];
		foreach ($fields[KitKeys::KEY_WORKS_LIST]->content['list'] as $work)
		{
			$fieldsByKey = $work->fields_by_key;
			$arr[$work->id] = [
				'title' => $work->title,
				'url' => JRoute::_($work->url),
			];
		}
		return $arr;
	}

	private function setGoodsList(): array
	{
		return $this->sparepartsList + $this->accessoriesList + $this->worksList;
	}

}