<?php
defined('_JEXEC') or die();
JImport('b0.Work.WorkKeys');

class WorkFiltersKeys
{
	//*** Filters Keys
	public const KEY_FILTER_CATEGORY = '#filters'. WorkKeys::KEY_CATEGORY .'value';
	public const KEY_FILTER_MODEL = '#filters'. WorkKeys::KEY_MODEL .'value';
	public const KEY_FILTER_GENERATION = '#filters'. WorkKeys::KEY_GENERATION .'value';
	public const KEY_FILTER_YEAR = '#filters'. WorkKeys::KEY_YEAR .'value';
	public const KEY_FILTER_MOTOR = '#filters'. WorkKeys::KEY_MOTOR .'value';
	public const KEY_FILTER_DRIVE = '#filters'. WorkKeys::KEY_DRIVE .'value';
}
