<?php
defined('_JEXEC') or die();
JImport('b0.Work.WorkIds');
JImport('b0.Maintenance.MaintenanceIds');
//JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

class ModB0PopularServicesHelper
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
//		$query->where("id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (".MaintenanceIds::ID_IS_BY_ORDER .",".WorkIds::ID_IS_BY_ORDER.")) AND field_value = '-1')");
//		$query->order('hits DESC')->order('RAND()');
		$query->order('RAND()');
		$db->setQuery($query, 0, $params->get('limit', '6'));
		$items = $db->loadObjectList();
		$result = [];
		foreach ($items as $item)
		{
			$sectionId = $item->section_id;
			$fields    = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
			$result[] = [
				'link' => self::setLink($item->id),
				'url'          => CobaltApi::getArticleLink($item->id, 'target="_blank"'),
				'image'        => self::setImage($sectionId, $item->id),
				'priceGeneral' => self::setPriceGeneral($sectionId, $fields),
				'priceSpecial' => self::setPriceSpecial($sectionId, $fields),
				'isSpecial'    => self::setIsSpecial($sectionId, $fields),
			];
		}
		return $result;
	}
	private static function setLink($itemId)
	{
		$record = ItemsStore::getRecord($itemId);
		return JRoute::_(Url::record($record));
	}
	
	private static function setImage($sectionId, $itemId) {
		switch ($sectionId) {
			case WorkIds::ID_SECTION:
				return CobaltApi::renderField($itemId, WorkIds::ID_IMAGE, 'list');
				break;
			case MaintenanceIds::ID_SECTION:
//				return CobaltApi::renderField($itemId, MaintenanceIds::ID_IMAGE, 'list');
				return '';
				break;
			default:
				return null;
		}
	}
	
	/**
	 * @param $sectionId
	 * @param $fields
	 *
	 * @return mixed|string|null
	 */
	private static function setPriceGeneral($sectionId, $fields)
	{
		switch ($sectionId) {
			case WorkIds::ID_SECTION:
				return $fields[WorkIds::ID_PRICE_GENERAL];
				break;
			case MaintenanceIds::ID_SECTION:
//				return $fields[MaintenanceIds::ID_PRICE_GENERAL];
				return '';
				break;
			default:
				return null;
		}
	}
	private static function setPriceSpecial($sectionId, $fields)
	{
		switch ($sectionId) {
			case WorkIds::ID_SECTION:
				return $fields[WorkIds::ID_PRICE_SPECIAL];
				break;
			case MaintenanceIds::ID_SECTION:
				return $fields[MaintenanceIds::ID_PRICE_SPECIAL];
				break;
			default:
				return null;
		}
	}
	private static function setIsSpecial($sectionId, $fields)
	{
		switch ($sectionId) {
			case WorkIds::ID_SECTION:
				return $fields[WorkIds::ID_IS_SPECIAL];
				break;
			case MaintenanceIds::ID_SECTION:
				return $fields[MaintenanceIds::ID_IS_SPECIAL];
				break;
			default:
				return null;
		}
	}
}
