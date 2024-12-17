<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
jimport('joomla.database.table');

JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_cobalt/tables', 'CobaltTable');
JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_cobalt/models', 'CobaltModel');

include_once __DIR__ . '/url.php';

class ItemsStore
{
	public static array $categories = [];
	public static array $sections = [];
	public static array $records = [];
	public static array $types = [];
	public static array $usercategories = [];
	public static $record_ids = null;

	public static function getSection($section_id)
	{
		if(!isset(self::$sections[$section_id])) {
			$section_model = JModelLegacy::getInstance('Section', 'CobaltModel');
			self::$sections[$section_id] = $section_model->getItem($section_id);
		}
		return self::$sections[$section_id];
	}

	public static function getRecord($record_id)
	{
		if(! isset(self::$records[$record_id])) {
            /** @var CobaltModelSection $rec_mod */
            $rec_mod = JModelLegacy::getInstance('Record', 'CobaltModel');
			self::$records[$record_id] = $rec_mod->getItem($record_id);
		}
		return self::$records[$record_id];
	}

	public static function getType($type_id)
	{
		if(! isset(self::$types[$type_id])) {
			self::$types[$type_id] = JModelLegacy::getInstance('Form', 'CobaltModel')->getRecordType($type_id);
		}
		return self::$types[$type_id];
	}

	public static function getUserCategory($ucategory_id)
	{
		if(!isset(self::$usercategories[$ucategory_id])) {
			$usercategory_model = JModelLegacy::getInstance('Usercategory', 'CobaltModel');
			self::$usercategories[$ucategory_id] = $usercategory_model->getItem($ucategory_id);
		}
		return self::$usercategories[$ucategory_id];
	}

	public static function getCategory($category_id)
	{
		if(array_key_exists($category_id, self::$categories)) {
			return self::$categories[$category_id];
		}

		require_once JPATH_ROOT . '/components/com_cobalt/models/category.php';
		$model = new CobaltModelCategory();
		self::$categories[$category_id] = $model->getItem($category_id);
		return @self::$categories[$category_id];
	}
}
