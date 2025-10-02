<?php
defined('_JEXEC') or die();

class FeedDefinitions
{
    // Новый пайплайн пишет файлы в отдельную директорию, чтобы не мешать legacy
    // В дальнейшем можно выровнять список фидов и их содержимое
    public const FEEDS = [
        'products-vesta-new' => [
            'isNeed' => true,
            'name' => 'Запчасти и аксессуары Веста (new)',
            'filePath' => '/uploads/feeds-new/feed-products-vesta-new.xml',
        ],
        'works-all-new' => [
            'isNeed' => true,
            'name' => 'Работы общий (new)',
            'filePath' => '/uploads/feeds-new/feed-works-all-new.xml',
        ],
    ];
}
