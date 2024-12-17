<?php
defined('_JEXEC') or die();
JImport('b0.Sparepart.SparepartKeys');
JImport('b0.Sparepart.SparepartIds');

class SparepartFiltersKeys
{
	//*** Filters Keys
	public const KEY_FILTER_CATEGORY = '#filters'. SparepartKeys::KEY_CATEGORY .'value';
	public const KEY_FILTER_MODEL = '#filters'. SparepartKeys::KEY_MODEL .'value';
	public const KEY_FILTER_GENERATION = '#filters'. SparepartKeys::KEY_GENERATION .'value';
	public const KEY_FILTER_YEAR = '#filters'. SparepartKeys::KEY_YEAR .'value';
	public const KEY_FILTER_MOTOR = '#filters'. SparepartKeys::KEY_MOTOR .'value';
	public const KEY_FILTER_DRIVE = '#filters'. SparepartKeys::KEY_DRIVE .'value';
	//*** Pre Filters Keys
	public const KEY_PRE_FILTER_GENERATION = 'index.php?option=com_cobalt&task=records.filter&section_id='. SparepartIds::ID_SECTION .
	'&Itemid=' . SparepartIds::ID_ITEM_ID .
	'&filter_name[0]=filter_' . SparepartKeys::KEY_GENERATION;
}
