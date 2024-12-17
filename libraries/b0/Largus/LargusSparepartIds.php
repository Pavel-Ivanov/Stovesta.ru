<?php
defined('_JEXEC') or die('');

class LargusSparepartIds
{
	// Global
	public const ID_SECTION = 2;
	public const ID_TYPE = 3;
	public const ID_ITEM_ID = 108;
	//***
	public const ID_SUBTITLE = '60';
	public const ID_SEARCH_SYNONYMS = '73';
	public const ID_MANUFACTURER = '33';
	public const ID_IMAGE = '28';
	//*** Codes
	public const ID_PRODUCT_CODE = '69';
	public const ID_VENDOR_CODE = '26';
	public const ID_ORIGINAL_CODE = '27';
	//*** Prices
	public const ID_PRICE_GENERAL = '30';
	public const ID_PRICE_SIMPLE = '100';
	public const ID_PRICE_SILVER = '101';
	public const ID_PRICE_GOLD = '102';
	public const ID_PRICE_DELIVERY = '103';
	public const ID_IS_SPECIAL = '104';
	public const ID_PRICE_SPECIAL = '32';
	public const ID_IS_ORIGINAL = '90';
	public const ID_IS_BY_ORDER = '89';
	//*** Availability
	public const ID_SEDOVA = '113';
	public const ID_KHIMIKOV = '114';
	public const ID_ZHUKOVA = '115';
	public const ID_KULTURY = '117';
	public const ID_PLANERNAYA = '116';
	//***
	public const ID_CHARACTERISTICS = '34';
	public const ID_DESCRIPTION = '29';
	public const ID_GALLERY = '112';
//	public const ID_VIDEO = '99';
	public const ID_VIDEO = '226';
	//*** Filters
	public const ID_CATEGORY = '50';
	public const ID_MODEL = '42';
	public const ID_GENERATION = '223';
	public const ID_BODY = '224';
	public const ID_YEAR = '47';
	public const ID_MOTOR = '43';
	public const ID_MODIFICATION = '56';
	public const ID_DRIVE = '153';
	//*** Relations
	public const ID_ANALOGS = '51';
	public const ID_ASSOCIATED = '61';
	public const ID_WORKS = '62';
	public const ID_ARTICLES = '75';
	public const ID_MAINTENANCE = '88';
	//*** Form
	public const ID_FORM_PRICE_GENERAL = 'field_' . self::ID_PRICE_GENERAL;
	public const ID_FORM_PRICE_SIMPLE = 'field_' . self::ID_PRICE_SIMPLE;
	public const ID_FORM_PRICE_SILVER = 'field_' . self::ID_PRICE_SILVER;
	public const ID_FORM_PRICE_GOLD = 'field_' . self::ID_PRICE_GOLD;
	public const ID_FORM_PRICE_DELIVERY = 'field_' . self::ID_PRICE_DELIVERY;
	public const ID_FORM_PRICE_SPECIAL = 'field_' . self::ID_PRICE_SPECIAL;

	public const ID_FORM_IS_SPECIAL_YES = 'boolyes' . self::ID_IS_SPECIAL;
	public const ID_FORM_IS_SPECIAL_NO = 'boolno' . self::ID_IS_SPECIAL;
 
	public const ID_FORM_IS_ORIGINAL_YES = 'boolyes' . self::ID_IS_ORIGINAL;
	public const ID_FORM_IS_ORIGINAL_NO = 'boolno' . self::ID_IS_ORIGINAL;

	public const ID_FORM_IS_BY_ORDER_YES = 'boolyes' . self::ID_IS_BY_ORDER;
	public const ID_FORM_IS_BY_ORDER_NO = 'boolno' . self::ID_IS_BY_ORDER;

	public const ID_FORM_SEDOVA = 'field_' . self::ID_SEDOVA;
	public const ID_FORM_KHIMIKOV = 'field_' . self::ID_KHIMIKOV;
	public const ID_FORM_ZHUKOVA = 'field_' . self::ID_ZHUKOVA;
	public const ID_FORM_KULTURY = 'field_' . self::ID_KULTURY;
	public const ID_FORM_PLANERNAYA = 'field_' . self::ID_PLANERNAYA;
}
