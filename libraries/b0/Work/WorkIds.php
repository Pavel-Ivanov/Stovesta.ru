<?php
defined('_JEXEC') or die();

class WorkIds
{
	public const ID_SECTION = 4;
	public const ID_TYPE = 5;
//***
	public const ID_SERVICE_CODE = '33';
	public const ID_SUBTITLE = '34';
	public const ID_SEARCH_SYNONYMS = '35';
	public const ID_APPROXIMATE_TIME = '112';
	public const ID_IMAGE = '223';
//*** Prices
	public const ID_PRICE_GENERAL = '37';
	public const ID_PRICE_SIMPLE = '67';
	public const ID_PRICE_SILVER = '68';
	public const ID_PRICE_GOLD = '69';
	public const ID_PRICE_FIRST_VISIT = '139';
	public const ID_IS_SPECIAL = '66';
	public const ID_PRICE_SPECIAL = '38';
	public const ID_IS_HIT = '238';
	public const ID_HIT_IMAGE = '239';
//***
	public const ID_DESCRIPTION = '36';
	public const ID_GALLERY = '131';
	public const ID_VIDEO = '224';
//*** Filters
	public const ID_CATEGORY = '221';
	public const ID_MODEL = '39';
	public const ID_GENERATION = '222';
	public const ID_YEAR = '40';
	public const ID_MOTOR = '41';
	public const ID_DRIVE = '130';
//*** Relations
	public const ID_SPAREPARTS = '43';
	public const ID_ACCESSORIES = '94';
	public const ID_WORKS = '132';
	public const ID_MAINTENANCE = '58';
	public const ID_ARTICLES = '215';
	
	//*** Form
	public const ID_FORM_PRICE_GENERAL = 'field_' . self::ID_PRICE_GENERAL;
	public const ID_FORM_PRICE_SIMPLE = 'field_' . self::ID_PRICE_SIMPLE;
	public const ID_FORM_PRICE_SILVER = 'field_' . self::ID_PRICE_SILVER;
	public const ID_FORM_PRICE_GOLD = 'field_' . self::ID_PRICE_GOLD;
	public const ID_FORM_PRICE_FIRST_VISIT = 'field_' . self::ID_PRICE_FIRST_VISIT;
	public const ID_FORM_PRICE_SPECIAL = 'field_' . self::ID_PRICE_SPECIAL;
	
	public const ID_FORM_IS_SPECIAL_YES = 'boolyes' . self::ID_IS_SPECIAL;
	public const ID_FORM_IS_SPECIAL_NO = 'boolno' . self::ID_IS_SPECIAL;
	
	//*** Categories
	public const ID_CATEGORY_VESTA = '74';
	public const ID_CATEGORY_XRAY = '75';
	public const ID_CATEGORY_GRANTA_FL = '165';
	public const ID_CATEGORY_LARGUS = '166';
}
