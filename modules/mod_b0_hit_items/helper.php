<?php
defined('_JEXEC') or die();
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Product.ProductConfig');

class ModB0HitItemsHelper
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
		$query->where("id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (".AccessoryIds::ID_IS_HIT .",".SparepartIds::ID_IS_HIT.")) AND field_value = '1')");
//		$query->order('hits DESC')->order('RAND()');
		$query->order('RAND()');
		$db->setQuery($query, 0, $params->get('limit', '6'));
		$items = $db->loadObjectList();
		$result = [];
		foreach ($items as $item) {
			$sectionId = $item->section_id;
			$fields    = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
            $isOriginal = self::setIsOriginal($sectionId, $fields);
            $priceGeneral = self::setPriceGeneral($sectionId, $fields);
            $result[] = [
                'url'          => CobaltApi::getArticleLink($item->id, 'target="_blank"'),
                'image'        => self::setImage($sectionId, $item->id),
                'priceGeneral' => $priceGeneral,
                'priceSpecial' => self::setPriceSpecial($sectionId, $fields),
                'isSpecial'    => self::setIsSpecial($sectionId, $fields),
                'isOriginal'    => $isOriginal,
                'isByOrder'    => self::setIsByOrder($sectionId, $fields),
                'priceGold' => self::setPriceGold($priceGeneral, $isOriginal, ProductConfig::PRICE_DISCOUNT_ORIGINAL_GOLD, ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_GOLD, 'Золотой уровень'),
			];
		}
		return $result;
	}
	private static function setImage($sectionId, $itemId) {
		switch ($sectionId) {
			case SparepartIds::ID_SECTION:
				return CobaltApi::renderField($itemId, SparepartIds::ID_HIT_IMAGE, 'list');
			case AccessoryIds::ID_SECTION:
				return CobaltApi::renderField($itemId, AccessoryIds::ID_HIT_IMAGE, 'list');
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
    private static function setPriceGold($priceGeneral, $isOriginal, $discountOriginal, $discountNonOriginal, $label = ''): int
    {
        return round($isOriginal ? $priceGeneral * $discountOriginal : $priceGeneral * $discountNonOriginal);
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
    private static function setIsOriginal($sectionId, $fields)
    {
        switch ($sectionId) {
            case SparepartIds::ID_SECTION:
                return $fields[SparepartIds::ID_IS_ORIGINAL];
            case AccessoryIds::ID_SECTION:
                return $fields[AccessoryIds::ID_IS_ORIGINAL];
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
