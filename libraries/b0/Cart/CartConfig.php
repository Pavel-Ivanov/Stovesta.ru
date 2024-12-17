<?php
defined('_JEXEC') or die();

/**
 * Class CartConfig
 * Константы конфигурации корзины
 */

class CartConfig
{
	public const CART_EMAIL_FROM = 'delivery@stovesta.ru';
	public const CART_EMAIL_FROM_NAME = 'Отдел доставки СтоВеста';
	public const CART_EMAIL_RECIPIENT = [
		'delivery-spb@stovesta.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'
	];
	
	public const CART_EMAIL_RECIPIENT_REGIONS = [
		'delivery-region@stovesta.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'
	];
	
	public const CART_ID_GAGARINA = 'ВВ-000001';
	public const CART_ID_KHIMIKOV = '00-000023';
	public const CART_ID_KULTURY = 'ФР-000004';
	public const CART_ID_ZHUKOVA = 'ФР-000005';
	public const CART_ID_PLANERNAYA = 'ФР-000008';
	
	public const CART_EMAIL_RECIPIENT_SHOP = [
		self::CART_ID_GAGARINA => ['sale@logan-shop.spb.ru', 'klientsky.servis@yandex.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'],
		self::CART_ID_KHIMIKOV => ['magazin2@logan-shop.spb.ru', 'klientsky.servis@yandex.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'],
		self::CART_ID_KULTURY => ['logan-shopbest@yandex.ru', 'klientsky.servis@yandex.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'],
		self::CART_ID_ZHUKOVA => ['magazin3@logan-shop.spb.ru', 'klientsky.servis@yandex.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'],
		self::CART_ID_PLANERNAYA => ['magazin7@stovesta.ru', 'klientsky.servis@yandex.ru', 'zakupki-logan-vesta@logan-shop.spb.ru'],
	];
	public const CART_EMAIL_SUBJECT_PREFIX = 'Доставка- заказ в ';
	public const CART_EMAIL_SUBJECT_PREFIX_MYSELF = 'Самовывоз- заказ в ';
	
	public const CART_ENABLE_SUCCESS_EMAIL = false;
	public const CART_ENABLE_SUCCESS_LOG = false;
	public const CART_ENABLE_REQUEST_LOG = false;
	
	public const ICON_RUB = '<i class="uk-icon-rub uk-text-muted"></i>';
	
	public const LIMIT_DELIVERY_CITY = 500;
	public const PRICE_DELIVERY_CITY = 300;
	public const LIMIT_DELIVERY_SATELLITES = 1500;
	public const PRICE_DELIVERY_SATELLITES = 500;
}
