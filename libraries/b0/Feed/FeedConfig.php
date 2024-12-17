<?php
defined('_JEXEC') or die();

class FeedConfig
{
	public const FEED_NAME = 'СтоВеста';    // Название
	public const FEED_COMPANY = 'СтоВеста';    // Название компании
	public const FEED_URL = 'https://stovesta.ru';    // Ссылка на сайт
	public const FEED_URL_SPAREPARTS = self::FEED_URL . '/spareparts/item/';   // Ссылка на раздел запчасти
	public const FEED_URL_ACCESSORIES = self::FEED_URL . '/accessories/item/'; // Ссылка на раздел аксессуары
	
	public const FEED_URL_NO_IMAGE = 'images/elements/no-photo.png';    //

	public const FEED_CURRENCY = 'RUB';    // Валюта
	public const FEED_CURRENCY_RATE = '1';    // Курс валюты
	public const FEED_PAYMENT_OPTIONS = 'Наличные, карта МИР.';
//	public const FEED_PAYMENT_OPTIONS = 'Наличные';
	public const FEED_SALES_NOTES_DELIVERY = 'При заказе от 500 руб- доставка бесплатно';
	
	// Массив опций доставки
	public const FEED_DELIVERY_OPTIONS = [
		[
			'cost' => '0',
			'days' => '1-3',
			'order-before' => ''
		],
	];
	
	public const FEED_LIMIT_DELIVERY_CITY = 500;
	public const FEED_PRICE_DELIVERY_CITY = 300;
	
	public const FEED_LIMIT_DELIVERY_SATELLITES = 1500;
	public const FEED_PRICE_DELIVERY_SATELLITES = 500;
//	public const FEED_DAYS = '1-2';
	
	// Параметры отправки почты
	public const FEED_EMAIL_FROM = 'admin@stovesta.ru';
	public const FEED_EMAIL_FROM_NAME  = 'Admin';
	public const FEED_EMAIL_RECIPIENT = ['p.ivanov@stovesta.ru'];
	public const FEED_EMAIL_SUBJECT = 'FEED full StoVesta';
}
