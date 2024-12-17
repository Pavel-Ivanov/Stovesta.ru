<?php
defined('_JEXEC') or die();

$config = [
	'с 2017 года выпуска' => [
		'type' => 'benzin',
		'motors' => [
			'ВАЗ-21129 (1.6 16V) / ВАЗ-21179 (1.8 16V)' => [
				'type' => 'benzin',
				'model'  => 'Lada Vesta SW',
				'motor'  => '1.6/1.8 16V',
				'years'  => 'с 2017',
				'freq'   => 15000,
				'th'     => 'ВАЗ-21129 (1.6 16V)<br> ВАЗ-21179 (1.8 16V)',
				'items'  => [
					[
						'milage' => 15000,
						'tdHref'   => '/maintenance/item/2349-tekhnicheskoe-obsluzhivanie-lada-vesta-sw-15000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 30000,
						'tdHref'   => '/maintenance/item/2350-tekhnicheskoe-obsluzhivanie-lada-vesta-sw-30000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 45000,
						'tdHref'   => '/maintenance/item/2351-tekhnicheskoe-obsluzhivanie-lada-vesta-sw-45000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 60000,
						'tdHref'   => '/maintenance/item/2352-tekhnicheskoe-obsluzhivanie-lada-vesta-sw-60000-km',
						'type'     => 'oil-ign'
					],
					[
						'milage' => 75000,
						'tdHref'   => '/maintenance/item/2353-tekhnicheskoe-obsluzhivanie-lada-vesta-sw-75000-km',
						'type'     => 'oil'
					],
					[
						'milage' => 90000,
						'tdHref'   => '/maintenance/item/2354-tekhnicheskoe-obsluzhivanie-lada-vesta-sw-90000-km',
						'type'     => 'oil-ign-liq'
					],
				]
			],
		],
	],
];
