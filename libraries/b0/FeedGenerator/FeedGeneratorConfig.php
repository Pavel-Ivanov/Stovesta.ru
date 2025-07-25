<?php
defined('_JEXEC') or die();

JImport('b0.Accessory.AccessoryIds');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Work.WorkIds');


class FeedGeneratorConfig
{
    public const FEED_GENERATOR_FEEDS = [
        'accesories-vesta' => [
            'type' => 'Accessory',
            'isNeed' => true,
            'name' => 'Аксессуары Веста',
            'filePath' => '/uploads/feeds/feed-accessories-vesta.xml',
            'sectionId' => AccessoryIds::ID_SECTION,
            'fieldAvailability' => AccessoryIds::ID_IS_BY_ORDER,
            'fieldModel' => AccessoryIds::ID_MODEL,
            'modelName' => 'Lada Vesta',
            'limit' => 0,
        ],
        'accesories-xray' => [
            'isNeed' => true,
            'name' => 'Аксессуары XRay',
            'filePath' => '/uploads/feeds/feed-accessories-xray.xml',
            'sectionId' => AccessoryIds::ID_SECTION,
            'fieldAvailability' => AccessoryIds::ID_IS_BY_ORDER,
            'fieldModel' => AccessoryIds::ID_MODEL,
            'modelName' => 'Lada XRay',
            'limit' => 0,
        ],
        'accesories-granta' => [
            'isNeed' => true,
            'name' => 'Аксессуары Гранта',
            'filePath' => '/uploads/feeds/feed-accessories-granta.xml',
            'sectionId' => AccessoryIds::ID_SECTION,
            'fieldAvailability' => AccessoryIds::ID_IS_BY_ORDER,
            'fieldModel' => AccessoryIds::ID_MODEL,
            'modelName' => 'Lada Granta FL',
            'limit' => 0,
        ],
        'accesories-largus' => [
            'isNeed' => true,
            'name' => 'Аксессуары Ларгус',
            'filePath' => '/uploads/feeds/feed-accessories-largus.xml',
            'sectionId' => AccessoryIds::ID_SECTION,
            'fieldAvailability' => AccessoryIds::ID_IS_BY_ORDER,
            'fieldModel' => AccessoryIds::ID_MODEL,
            'modelName' => 'Lada Largus',
            'limit' => 0,
        ],
        /*		'accesories-iskra' => [
                    'isNeed' => true,
                    'name' => 'Аксессуары Искра',
                    'filePath' => '/uploads/feeds/feed-accessories-iskra.xml',
                    'sectionId' => AccessoryIds::ID_SECTION,
                    'fieldAvailability' => AccessoryIds::ID_IS_BY_ORDER,
                    'fieldModel' => AccessoryIds::ID_MODEL,
                    'modelName' => 'Lada Iskra',
                    'limit' => 0,
                ],*/
        'spareparts-vesta' => [
            'isNeed' => true,
            'name' => 'Запчасти Веста',
            'filePath' => '/uploads/feeds/feed-spareparts-vesta.xml',
            'sectionId' => SparepartIds::ID_SECTION,
            'fieldAvailability' => SparepartIds::ID_IS_BY_ORDER,
            'fieldModel' => SparepartIds::ID_MODEL,
            'modelName' => 'Lada Vesta',
            'limit' => 0,
        ],
        'spareparts-xray' => [
            'isNeed' => true,
            'name' => 'Запчасти XRay',
            'filePath' => '/uploads/feeds/feed-spareparts-xray.xml',
            'sectionId' => SparepartIds::ID_SECTION,
            'fieldAvailability' => SparepartIds::ID_IS_BY_ORDER,
            'fieldModel' => SparepartIds::ID_MODEL,
            'modelName' => 'Lada XRay',
            'limit' => 0,
        ],
        'spareparts-granta' => [
            'isNeed' => true,
            'name' => 'Запчасти Гранта',
            'filePath' => '/uploads/feeds/feed-spareparts-granta.xml',
            'sectionId' => SparepartIds::ID_SECTION,
            'fieldAvailability' => SparepartIds::ID_IS_BY_ORDER,
            'fieldModel' => SparepartIds::ID_MODEL,
            'modelName' => 'Lada Granta FL',
            'limit' => 0,
        ],
        'spareparts-largus' => [
            'isNeed' => true,
            'name' => 'Запчасти Ларгус',
            'filePath' => '/uploads/feeds/feed-spareparts-largus.xml',
            'sectionId' => SparepartIds::ID_SECTION,
            'fieldAvailability' => SparepartIds::ID_IS_BY_ORDER,
            'fieldModel' => SparepartIds::ID_MODEL,
            'modelName' => 'Lada Largus',
            'limit' => 0,
        ],
        /*		'spareparts-iskra' => [
                    'isNeed' => true,
                    'name' => 'Запчасти Искра',
                    'filePath' => '/uploads/feeds/feed-spareparts-iskra.xml',
                    'sectionId' => SparepartIds::ID_SECTION,
                    'fieldAvailability' => SparepartIds::ID_IS_BY_ORDER,
                    'fieldModel' => SparepartIds::ID_MODEL,
                    'modelName' => 'Lada Iskra',
                    'limit' => 0,
                ],*/
        'works-vesta' => [
            'isNeed' => true,
            'name' => 'Работы Веста',
            'filePath' => '/uploads/feeds/feed-works-vesta.xml',
            'sectionId' => WorkIds::ID_SECTION,
            'fieldAvailability' => 1,
            'fieldModel' => WorkIds::ID_MODEL,
            'modelName' => 'Lada Vesta',
            'limit' => 0,
        ],

    ];
}