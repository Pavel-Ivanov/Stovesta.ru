<?php
defined('_JEXEC') or die();

class FeedGeneratorConfig
{
    // Общая информация о магазине (новый пайплайн)
    public const FEED_NAME = 'СтоВеста';
    public const FEED_COMPANY = 'СтоВеста';
    public const FEED_URL = 'https://stovesta.ru';

    // Валюта
    public const FEED_CURRENCY = 'RUB';
    public const FEED_CURRENCY_RATE = '1';

    // URL по умолчанию для отсутствующего изображения
    public const FEED_URL_NO_IMAGE = 'images/elements/no-photo.png';

    // Опции доставки (минимальный набор)
    public const FEED_DELIVERY_OPTIONS = [
        [
            'cost' => '0',
            'days' => '1-3',
            'order-before' => ''
        ],
    ];

    // Параметры отправки почты для уведомлений нового пайплайна
    public const FEED_EMAIL_FROM = 'admin@stovesta.ru';
    public const FEED_EMAIL_FROM_NAME  = 'Admin';
    public const FEED_EMAIL_RECIPIENT = ['p.ivanov@stovesta.ru'];
    public const FEED_EMAIL_SUBJECT = 'FEED Generator (new)';
}
