<?php
defined('_JEXEC') or die();
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Accessory.AccessoryKeys');

class AccessoryFiltersKeys
{
	
	//*** Filters Keys
	public const KEY_FILTER_MODEL = '#filters'. AccessoryKeys::KEY_MODEL .'value';
	public const KEY_FILTER_BODY = '#filters'. AccessoryKeys::KEY_BODY .'value';
	public const KEY_FILTER_YEAR = '#filters'. AccessoryKeys::KEY_YEAR .'value';
	public const KEY_FILTER_MOTOR = '#filters'. AccessoryKeys::KEY_MOTOR .'value';
	public const KEY_FILTER_DRIVE = '#filters'. AccessoryKeys::KEY_DRIVE .'value';
	
	//*** Pre Filters Keys
	const KEY_PRE_FILTER_BODY = 'index.php?option=com_cobalt&task=records.filter
		&section_id='. AccessoryIds::ID_SECTION .
		'&Itemid=' . AccessoryIds::ID_ITEM_ID .
		'&filter_name[0]=filter_' . AccessoryKeys::KEY_BODY;
//		'&filter_val[0]=';
}
