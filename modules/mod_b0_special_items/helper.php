<?php
defined('_JEXEC') or die();
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
//JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

class ModB0SpecialItemsHelper
{
	static public function getItems($params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select("id,section_id,fields");
		$query->from("#__js_res_record");
		$query->where("id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (".AccessoryIds::ID_IS_BY_ORDER .",".SparepartIds::ID_IS_BY_ORDER.")) AND field_value = '-1')");
		$query->where("id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (".AccessoryIds::ID_IS_SPECIAL .",".SparepartIds::ID_IS_SPECIAL.")) AND field_value = '1')");
		$query->where("published = 1");
		$query->where("section_id IN (".AccessoryIds::ID_SECTION .",".SparepartIds::ID_SECTION.")");
		$query->order("RAND()");
		$db->setQuery($query, 0,$params->get('limit', '6'));
		$items = $db->loadObjectList();
		$result = [];
		foreach ($items as $item)
		{
			$sectionId = $item->section_id;
			$fields    = json_decode($item->fields, true);
			$result[] = [
				'url'          => CobaltApi::getArticleLink($item->id, 'target="_blank"'),
				'image'        => self::setImage($sectionId, $item->id),
				'priceGeneral' => self::setPriceGeneral($sectionId, $fields),
				'priceSpecial' => self::setPriceSpecial($sectionId, $fields),
				'isSpecial'    => self::setIsSpecial($sectionId, $fields),
				'isByOrder'    => self::setIsByOrder($sectionId, $fields),
			];
		};
		return $result;
	}
	private static function setImage($sectionId, $itemId) {
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return CobaltApi::renderField($itemId, SparepartIds::ID_IMAGE, 'list');
				break;
			case AccessoryIds::ID_SECTION:
				return CobaltApi::renderField($itemId, AccessoryIds::ID_IMAGE, 'list');
				break;
			default:
				return null;
		}
	}
	private static function setPriceGeneral($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_PRICE_GENERAL];
				break;
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_PRICE_GENERAL];
				break;
			default:
				return null;
		}
	}
	private static function setPriceSpecial($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_PRICE_SPECIAL];
				break;
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_PRICE_SPECIAL];
				break;
			default:
				return null;
		}
	}
	private static function setIsSpecial($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_IS_SPECIAL];
				break;
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_IS_SPECIAL];
				break;
			default:
				return null;
		}
	}
	private static function setIsByOrder($sectionId, $fields)
	{
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return $fields[SparepartIds::ID_IS_BY_ORDER];
				break;
			case AccessoryIds::ID_SECTION:
				return $fields[AccessoryIds::ID_IS_BY_ORDER];
				break;
			default:
				return null;
		}
	}
}