<?php
defined('_JEXEC') or die();
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
//JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

class ModB0PopularItemsHelper
{
	static public function getItems($params)
	{
		$sectionId = $params->get('section_id');
		$typeId = $params->get('type_id');
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('id,section_id,fields');
		$query->from('#__js_res_record');
		$query->where('section_id = ' . $sectionId)->where('type_id = ' . $typeId)->where('published = 1');
		$query->where("id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (".AccessoryIds::ID_IS_BY_ORDER .",".SparepartIds::ID_IS_BY_ORDER.")) AND field_value = '-1')");
//		$query->order('hits DESC')->order('RAND()');
		$query->order('RAND()');
		$db->setQuery($query, 0, $params->get('limit', '6'));
		$items = $db->loadObjectList();
		$result = [];
		foreach ($items as $item) {
			$sectionId = $item->section_id;
			$fields    = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
			$result[] = [
				'url'          => CobaltApi::getArticleLink($item->id, 'target="_blank"'),
				'image'        => self::setImage($sectionId, $item->id),
				'priceGeneral' => self::setPriceGeneral($sectionId, $fields),
				'priceSpecial' => self::setPriceSpecial($sectionId, $fields),
				'priceGold' => self::setPriceGold($sectionId, $fields),
				'isSpecial'    => self::setIsSpecial($sectionId, $fields),
				'isByOrder'    => self::setIsByOrder($sectionId, $fields),
			];
		}
		shuffle($result);
		return $result;
	}
	private static function setImage($sectionId, $itemId) {
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return CobaltApi::renderField($itemId, SparepartIds::ID_IMAGE, 'list');
			case AccessoryIds::ID_SECTION:
				return CobaltApi::renderField($itemId, AccessoryIds::ID_IMAGE, 'list');
			default:
				return null;
		}
	}
	private static function setPriceGeneral($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_PRICE_GENERAL];
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_PRICE_GENERAL];
			default:
				return null;
		}
	}
	private static function setPriceSpecial($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_PRICE_SPECIAL];
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_PRICE_SPECIAL];
			default:
				return null;
		}
	}
	private static function setPriceGold($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_PRICE_GOLD];
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_PRICE_GOLD];
			default:
				return null;
		}
	}
	private static function setIsSpecial($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_IS_SPECIAL];
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_IS_SPECIAL];
			default:
				return null;
		}
	}
	private static function setIsByOrder($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_IS_BY_ORDER];
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_IS_BY_ORDER];
			default:
				return null;
		}
	}
}
