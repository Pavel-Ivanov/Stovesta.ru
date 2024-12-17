<?php
defined('_JEXEC') or die();

class SparepartIds
{
	public const ID_SECTION = 1;
	public const ID_TYPE = '1';
	public const ID_ITEM_ID = 190;
	//***
	public const ID_SUBTITLE = '1';
	public const ID_SEARCH_SYNONYMS = '2';
	public const ID_MANUFACTURER = '7';
	public const ID_IMAGE = '3';
	//*** Codes
	public const ID_PRODUCT_CODE = '4';
	public const ID_VENDOR_CODE = '5';
	public const ID_ORIGINAL_CODE = '6';
	//*** Prices
	public const ID_PRICE_GENERAL = '8';
	public const ID_PRICE_SIMPLE = '46';
	public const ID_PRICE_SILVER = '63';
	public const ID_PRICE_GOLD = '64';
	public const ID_PRICE_DELIVERY = '65';
	public const ID_IS_SPECIAL = '47';
	public const ID_PRICE_SPECIAL = '9';
	public const ID_IS_ORIGINAL = '10';
	public const ID_IS_BY_ORDER = '11';
	public const ID_IS_HIT = '235';
	public const ID_IS_SALES = '236';
	public const ID_HIT_IMAGE = '237';
	//*** Availability
	public const ID_SEDOVA = '117';
	public const ID_KHIMIKOV = '230';
	public const ID_ZHUKOVA = '173';
	public const ID_KULTURY = '174';
	public const ID_PLANERNAYA = '212';
	//***
	public const ID_CHARACTERISTICS = '21';
	public const ID_DESCRIPTION = '20';
	public const ID_GALLERY = '133';
	public const ID_VIDEO = '225';
	//*** Filters
	public const ID_CATEGORY = '219';
	public const ID_MODEL = '13';
	public const ID_GENERATION = '220';
	public const ID_BODY = '228';
	public const ID_YEAR = '14';
	public const ID_MOTOR = '15';
	public const ID_DRIVE = '134';
	//*** Relations
	public const ID_ANALOGS = '16';
	public const ID_ASSOCIATED = '32';
	public const ID_WORKS = '42';
	public const ID_ARTICLES = '214';
	public const ID_MAINTENANCE = '60';
	//*** Yandex Market
	public const ID_YM_UPLOAD_ENABLE = '193';
	public const ID_YM_TITLE = '217';
	public const ID_YM_CATEGORY = '195';
	public const ID_YM_IMAGE = '194';
	
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
	public const ID_CATEGORY_VESTA = '2';
	public const ID_CATEGORY_XRAY = '3';
	public const ID_CATEGORY_GRANTA_FL = '163';
	public const ID_CATEGORY_LARGUS = '164';
}
