<?php
defined('_JEXEC') or die();

class LargusAccessoryIds
{
	// Global
	public const ID_SECTION = '9';
	public const ID_TYPE = '10';
	public const ID_ITEM_ID = 682;
	// Categories
	public const ID_CATEGORY_LARGUS_WAGON = '78';
	public const ID_CATEGORY_LARGUS_WAN = '79';
	public const ID_CATEGORY_LARGUS_CROSS = '80';
	public const ID_CATEGORY_UNIVERSAL = '81';
	//Fields
	public const ID_SUBTITLE = '118';
	public const ID_SEARCH_SYNONYMS = '119';
	public const ID_MANUFACTURER = '120';
	public const ID_IMAGE = '121';
	//*** Codes
	public const ID_PRODUCT_CODE = '122';
	public const ID_VENDOR_CODE = '123';
	public const ID_ORIGINAL_CODE = '124';
	//*** Prices
	public const ID_PRICE_GENERAL = '125';
	public const ID_PRICE_SIMPLE = '126';
	public const ID_PRICE_SILVER = '127';
	public const ID_PRICE_GOLD = '128';
	public const ID_PRICE_DELIVERY = '129';
	public const ID_IS_SPECIAL = '130';
	public const ID_PRICE_SPECIAL = '131';
	public const ID_IS_ORIGINAL = '132';
	public const ID_IS_BY_ORDER = '133';
	//*** Availability
	public const ID_SEDOVA = '134';
	public const ID_KHIMIKOV = '135';
	public const ID_ZHUKOVA = '136';
	public const ID_KULTURY = '137';
	public const ID_PLANERNAYA = '138';
	//***
	public const ID_CHARACTERISTICS = '139';
	public const ID_DESCRIPTION = '140';
	public const ID_GALLERY = '141';
//	public const ID_VIDEO = '142';
	public const ID_VIDEO = '225';
	//*** Filters
	public const ID_MODEL = '143';
	public const ID_GENERATION = '216';
	public const ID_BODY = '217';
	public const ID_YEAR = '144';
	public const ID_MOTOR = '145';
	public const ID_DRIVE = '146';
	//*** Relations
	public const ID_ANALOGS = '147';
	public const ID_ASSOCIATED = '148';
	public const ID_WORKS = '149';
	public const ID_ARTICLES = '218';
	public const ID_KITS = '151';
	public const ID_BUNDLES = '152';
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
