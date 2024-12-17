<?php
defined('_JEXEC') or die();

/**
 * Class YmlConfig
 * Константы конфигурации
 */
class YmlConfig
{
    public const YML_MODE_FULL = 'full';
    public const YML_MODE_MARKET = 'market';
    public const YML_MODE_OFFERS_ONLY = 'offers_only';
    public const YML_MODE_MARKET_AVAILABLE = 'market_available';

	public const YML_ITEMS_LIMIT = 0;  // Ограничение количества записей в запросе
    public const YML_FILE_PATH_FULL = '/yml-full.xml';    // Относительный путь конечного файла для Яндекс Товары и Цены
    public const YML_FILE_PATH_MARKET = '/yml-market.xml';    // Относительный путь файла для Яндекс Маркет
    public const YML_FILE_PATH_OFFERS_ONLY = '/yml-offers-only.xml';
    public const YML_FILE_PATH_MARKET_AVAILABLE = '/yml-market-available.xml';

    public const YML_NAME = 'StoVesta';    // Название
	public const YML_COMPANY = 'StoVesta';    // Название компании
	public const YML_URL = 'https://stovesta.ru';    // Ссылка на сайт
	public const YML_URL_SPAREPARTS = self::YML_URL . '/spareparts/item/';   // Ссылка на раздел запчасти
	public const YML_URL_ACCESSORIES = self::YML_URL . '/accessories/item/'; // Ссылка на раздел аксессуары

    public const YML_URL_NO_IMAGE = 'images/elements/no-photo.png';    //
    public const YML_IS_NEED_REMOTE = 1;    // Нужно-ли загружать удаленный файл
    public const YML_URL_REMOTE = 'https://logan-shop.spb.ru/yml-offers-only.xml';    // Ссылка на удаленный файл

    public const YML_CURRENCY = 'RUB';    // Валюта
	public const YML_CURRENCY_RATE = '1';    // Курс валюты
	public const YML_PAYMENT_OPTIONS = 'Наличные, Visa/Mastercard.';
//	public const YML_PAYMENT_OPTIONS = 'Наличные';
	public const YML_SALES_NOTES_DELIVERY = 'При заказе от 500 руб- доставка бесплатно';
	
	// Id поля Аксессуары
	public const YML_FIELD_ID = '178';
	
	// Массив опций доставки
	public const YML_DELIVERY_OPTIONS = [
		[
			'cost' => '0',
			'days' => '1-3',
			'order-before' => ''
		],
	];
	
	public const YML_LIMIT_DELIVERY_CITY = 500;
	public const YML_PRICE_DELIVERY_CITY = 300;
	
	public const YML_LIMIT_DELIVERY_SATELLITES = 1500;
	public const YML_PRICE_DELIVERY_SATELLITES = 500;
//	public const YML_DAYS = '1-2';
	
	// Параметры отправки почты
	public const YML_EMAIL_FROM = 'admin@stovesta.ru';
	public const YML_EMAIL_FROM_NAME  = 'Admin';
	public const YML_EMAIL_RECIPIENT = ['p.ivanov@stovesta.ru'];
	public const YML_EMAIL_SUBJECT = 'YML full StoVesta';
}
