<?php
defined('_JEXEC') or die();

class AccessoryIds
{
	public const ID_SECTION = 6;
	public const ID_TYPE = '7';
	public const ID_ITEM_ID = 223;
	//***
	public const ID_SUBTITLE = '70';
	public const ID_SEARCH_SYNONYMS = '71';
	public const ID_MANUFACTURER = '72';
	public const ID_IMAGE = '73';
	//*** Codes
	public const ID_PRODUCT_CODE = '74';
	public const ID_VENDOR_CODE = '75';
	public const ID_ORIGINAL_CODE = '76';
	//*** Prices
	public const ID_PRICE_GENERAL = '77';
	public const ID_PRICE_SIMPLE = '78';
	public const ID_PRICE_SILVER = '79';
	public const ID_PRICE_GOLD = '80';
	public const ID_PRICE_DELIVERY = '81';
	public const ID_IS_SPECIAL = '82';
	public const ID_PRICE_SPECIAL = '83';
	public const ID_IS_ORIGINAL = '84';
	public const ID_IS_BY_ORDER = '85';
	public const ID_IS_HIT = '232';
	public const ID_IS_SALES = '233';
	public const ID_HIT_IMAGE = '234';
	//*** Availability
	public const ID_SEDOVA = '119';
	public const ID_KHIMIKOV = '229';
	public const ID_ZHUKOVA = '170';
	public const ID_KULTURY = '171';
	public const ID_PLANERNAYA = '211';
	//***
	public const ID_CHARACTERISTICS = '86';
	public const ID_DESCRIPTION = '87';
	public const ID_GALLERY = '140';
	public const ID_VIDEO = '231';
	//*** Filters
	public const ID_MODEL = '88';
	public const ID_GENERATION = '226';
	public const ID_BODY = '218';
	public const ID_YEAR = '89';
	public const ID_MOTOR = '90';
	public const ID_DRIVE = '175';
	//*** Relations
	public const ID_ANALOGS = '91';
	public const ID_ASSOCIATED = '92';
	public const ID_WORKS = '93';
	public const ID_ARTICLES = '213';
	//*** Yandex Market
	public const ID_YM_UPLOAD_ENABLE = '176';
	public const ID_YM_TITLE = '216';
	public const ID_YM_CATEGORY = '178';
	public const ID_YM_IMAGE = '177';
	
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
	
	//*** Categories
	public const ID_CATEGORY_VESTA = '144';
	public const ID_CATEGORY_XRAY = '147';
	public const ID_CATEGORY_GRANTA_FL = '161';
	public const ID_CATEGORY_LARGUS = '162';
	public const ID_CATEGORY_UNIVERSAL = '160';
}
