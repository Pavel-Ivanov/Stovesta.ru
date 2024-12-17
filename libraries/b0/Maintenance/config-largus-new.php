<?php
defined('_JEXEC') or die();

$config = [
	'2012 - 2015 года выпуска' => [
		'type' => 'benzin',
		'motors' => [
			'K7M (1.6 8V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 8V',
				'years'  => '2012-2015',
				'freq'   => 15000,
				'th'     => 'K7M (1.6 8V)',
				'operations' => [
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '-',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6811-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6812-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6813-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6814-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign-grm'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6815-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6816-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-liq'
					],
				]
			],
			'K4M (1.6 16V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 16V',
				'years'  => '2012-2015',
				'freq'   => 15000,
				'th'     => 'K4M (1.6 16V)',
				'operations' => [
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '-',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6817-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6818-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6819-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6820-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign-grm'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6821-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6822-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-liq'
					],
				]
			]
		],
	],
	'2016 - 2017 года выпуска' => [
		'type' => 'benzin',
		'motors' => [
			'ВАЗ-11189 (1.6 8V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 8V',
				'years'  => '2016-2017',
				'freq'   => 15000,
				'th'     => 'ВАЗ-11189 (1.6 8V)',
				'operations' => [
					[
						'name' => 'Регулировка зазоров клапанов',
						'15000' => '+',
						'30000' => '-',
						'45000' => '+',
						'60000' => '-',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '+',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '+',
						'90000' => '-',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6823-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6829-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6830-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6831-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6832-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil-liq'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6833-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-grm'
					],
				]
			],
			'K4M (1.6 16V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 16V',
				'years'  => '2016-2017',
				'freq'   => 15000,
				'th'     => 'K4M (1.6 16V)',
				'operations' => [
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6854-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6824-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6825-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6826-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6827-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6828-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-grm-liq'
					],
				]
			]
		],
	],
	'с 2018 года выпуска' => [
		'type' => 'benzin',
		'motors' => [
			'ВАЗ-11182 (1.6 8V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 8V',
				'years'  => 'с 2020',
				'freq'   => 15000,
				'th'     => 'ВАЗ-11182 (1.6 8V)',
				'operations' => [
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '+',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '+',
						'90000' => '-',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6840-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6841-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6842-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6843-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6844-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil-liq'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6845-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-grm'
					],
				]
			],
			'ВАЗ-11189 (1.6 8V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 8V',
				'years'  => 'с 2018',
				'freq'   => 15000,
				'th'     => 'ВАЗ-11189 (1.6 8V)',
				'operations' => [
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена топливного фильтра',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '+',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '+',
						'90000' => '-',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Регулировка зазоров клапанов',
						'15000' => '+',
						'30000' => '-',
						'45000' => '+',
						'60000' => '-',
						'75000' => '+',
						'90000' => '+',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6834-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6835-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6836-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6837-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6838-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil-liq'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6839-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-grm'
					],
				]
			],
			'ВАЗ-21129 (1.6 16V)' => [
				'type' => 'benzin',
				'model'  => 'Лада Ларгус',
				'motor'  => '1.6 16V',
				'years'  => 'с 2018',
				'freq'   => 15000,
				'th'     => 'ВАЗ-21129 (1.6 16V)',
				'operations' => [
					[
						'name' => 'Замена масла в двигателе и масляного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена воздушного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена салонного фильтра',
						'15000' => '+',
						'30000' => '+',
						'45000' => '+',
						'60000' => '+',
						'75000' => '+',
						'90000' => '+',
					],
					[
						'name' => 'Замена свечей зажигания',
						'15000' => '-',
						'30000' => '+',
						'45000' => '-',
						'60000' => '+',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена тормозной жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '+',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
					[
						'name' => 'Замена охлаждающей жидкости',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '+',
						'90000' => '-',
					],
					[
						'name' => 'Замена ремня ГРМ',
						'15000' => '-',
						'30000' => '-',
						'45000' => '-',
						'60000' => '-',
						'75000' => '-',
						'90000' => '+',
					],
				],
				'links'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/6846-tekhnicheskoe-obsluzhivanie-lada-largus-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/6847-tekhnicheskoe-obsluzhivanie-lada-largus-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/6848-tekhnicheskoe-obsluzhivanie-lada-largus-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/6849-tekhnicheskoe-obsluzhivanie-lada-largus-60000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/6850-tekhnicheskoe-obsluzhivanie-lada-largus-75000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/6851-tekhnicheskoe-obsluzhivanie-lada-largus-90000-km',
						'type'     => 'oil-ign-grm-liq'
					],
				]
			],
		],
	],
];
