<?php
defined('_JEXEC') or die();

JImport('b0.Service.ServiceConfig');
class Service
{
	public int $id;
	public array $controls;
	public array $tabsTemplate;
	public array $tabs;
	public $serviceIds;

	//*** Core
	public string $url;
	public string $title;
	
	//*** Fields
	public array $subtitle;
	public array $serviceCode;
	public array $description;
	
	public bool $isSpecial;
//	public bool $isGeneral;
	public array $priceGeneral;
	public array $priceSpecial;
	public array $priceSimple;
	public array $priceSilver;
	public array $priceGold;
	public array $priceFirstVisit;
	public array $priceCurrent;
	
	public array $image;
	public array $video;
	public array $gallery;
	
	public array $mpn;
	
	public array $model;
	public array $generation;
	public array $year;
	public array $motor;
	public array $drive;
	
	public array $openGraph;
	
	//*** Meta
	public string $siteName;
	public string $metaTitle;
	public string $metaDescription;
	//***
	public array $phoneService1;
	public array $phoneService2;
	public string $moduleOrder;
	public string $moduleCallback;
	public string $moduleMinibanners;
	public string $firstVisitUrl;
	public string $guaranteeUrl;
	public string $discountsUrl;
	public string $vkUrl;
	public string $vkIcon;
	public string $discountCardIcon;
	//*** Metrics
//	public int $yandex;
//	public string $yandexId;
//	public string $yandexGoalOrder;
//	public string $yandexGoalCallback;
//	public int $google;
//	public string $googleGoalOrder;
//	public string $googleGoalCallback;
	//*** Social
	public string $hits;

	public function __construct($item, $serviceIds, JRegistry $paramsRecord, JRegistry $paramsApp)
	{
		$this->serviceIds = $serviceIds;
		
		$this->tabsTemplate = [
			$serviceIds::ID_SPAREPARTS => [
				'title' => ServiceConfig::TITLE_SPAREPARTS,
				'isActive' => 1,
			],
			$serviceIds::ID_ACCESSORIES => [
				'title' => ServiceConfig::TITLE_ACCESSORIES,
				'isActive' => 0,
			],
			$serviceIds::ID_WORKS => [
				'title' => ServiceConfig::TITLE_WORKS,
				'isActive' => 0,
			],
			$serviceIds::ID_ARTICLES => [
				'title' => ServiceConfig::TITLE_ARTICLES,
				'isActive' => 0,
			],
			$serviceIds::ID_GALLERY => [
				'title' => ServiceConfig::TITLE_GALLERY,
				'isActive' => 0,
			],
		];
		
		$fields = $item->fields_by_id;
		
		//*** Core
		$this->id = $item->id;
		$this->url = $item->url;
		$this->title = $item->title;
		//*** Fields
		$this->subtitle = $this->setField($fields[$serviceIds::ID_SUBTITLE], 'Подзаголовок');
		$this->serviceCode = $this->setField($fields[$serviceIds::ID_SERVICE_CODE], 'Код услуги');
		
		$this->image = $this->setImage($fields[$serviceIds::ID_IMAGE]);
		$this->video = $this->setVideo($fields[$serviceIds::ID_VIDEO], $paramsRecord->get('tmpl_core.default_video'), $item->title);
		$this->gallery = isset($fields[$serviceIds::ID_GALLERY]->result) ? $this->setGallery($fields[$serviceIds::ID_GALLERY]) : [];
		
		$this->description = $this->setField($fields[$serviceIds::ID_DESCRIPTION], 'Описание');
		
		$this->isSpecial = $this->setFieldBoolean($fields[$serviceIds::ID_IS_SPECIAL]);
//		$this->isGeneral = !$this->isSpecial;
		$this->priceGeneral = $this->setField($fields[$serviceIds::ID_PRICE_GENERAL], 'Цена');
		$this->priceSpecial = $this->setField($fields[$serviceIds::ID_PRICE_SPECIAL], 'Специальная цена');
		$this->priceSimple = $this->setFieldPrice($fields[$serviceIds::ID_PRICE_GENERAL]->raw, ServiceConfig::DISCOUNT_PRICE_SIMPLE, 'Стандартный уровень');
		$this->priceSilver = $this->setFieldPrice($fields[$serviceIds::ID_PRICE_GENERAL]->raw, ServiceConfig::DISCOUNT_PRICE_SILVER, 'Серебряный уровень');
		$this->priceGold = $this->setFieldPrice($fields[$serviceIds::ID_PRICE_GENERAL]->raw, ServiceConfig::DISCOUNT_PRICE_GOLD, 'Золотой уровень');
		$this->priceFirstVisit = $this->setFieldPrice($fields[$serviceIds::ID_PRICE_GENERAL]->raw, ServiceConfig::DISCOUNT_PRICE_FIRST_VISIT, 'Цена при первом визите');
		$this->priceCurrent = $this->isSpecial ? $this->priceSpecial : $this->priceGeneral;
		$this->mpn = $this->setField($fields[$serviceIds::ID_SERVICE_CODE]);
		//*** Filters
		$this->model = $this->setField($fields[$serviceIds::ID_MODEL], 'Модель');
		$this->generation = $this->setField($fields[$serviceIds::ID_GENERATION], 'Поколение');
		$this->year = $this->setField($fields[$serviceIds::ID_YEAR], 'Год выпуска');
		$this->motor = $this->setField($fields[$serviceIds::ID_MOTOR], 'Мотор');
		$this->drive = $this->setField($fields[$serviceIds::ID_DRIVE], 'Привод');
		
		$this->openGraph = $this->setOpenGraph($item);
		
		$this->siteName = JFactory::getApplication()->get('sitename');
        $this->metaTitle = $this->setMetaTitle($item);
        $this->metaDescription = $this->setMetaDescription($item);
//		$this->metaTitle = $item->title.' в '. $this->siteName . ' за ' . $this->priceCurrent['value'] . ' рублей';
//		$this->metaDescription = $item->title . ' в ' . $this->siteName . ' за ' . $this->priceCurrent['value'] . ' рублей. ' .
//			$item->title . '- описание, фото, рекомендуемые запчасти и аксессуары.';

		$this->controls = $item->controls;
		$this->tabs = $this->setTabs($fields);
		
//		$this->yandex = $paramsRecord->get('tmpl_core.yandex', 0);
//		$this->yandexId = $paramsRecord->get('tmpl_core.yandex_id', '');
//		$this->yandexGoalOrder = $paramsRecord->get('tmpl_core.yandex_goal_order', '');
//		$this->yandexGoalCallback = $paramsRecord->get('tmpl_core.yandex_goal_callback', '');
//		$this->google = $paramsRecord->get('tmpl_core.google', 0);
//		$this->googleGoalOrder = $paramsRecord->get('tmpl_core.google_goal_order', '');
//		$this->googleGoalCallback = $paramsRecord->get('tmpl_core.google_goal_callback', '');
		
		$this->phoneService1 = [
			'url' => 'tel:'.str_ireplace('-', '', $paramsRecord->get('tmpl_core.phone_1', '')),
			'phone' => $paramsRecord->get('tmpl_core.phone_1', ''),
		];
		$this->phoneService2 = [
			'url' => 'tel:'.str_ireplace('-', '', $paramsRecord->get('tmpl_core.phone_2', '')),
			'phone' => $paramsRecord->get('tmpl_core.phone_2', ''),
		];
		
		$this->moduleOrder = $paramsRecord->get('tmpl_core.module_order', '');
		$this->moduleCallback = $paramsRecord->get('tmpl_core.module_callback', '');
		$this->moduleMinibanners = $paramsRecord->get('tmpl_core.module_minibanners', '');
		
		$this->firstVisitUrl = $paramsRecord->get('tmpl_core.first_visit_url', '');
		$this->guaranteeUrl = $paramsRecord->get('tmpl_core.guarantee_url', '');
		$this->discountsUrl = $paramsRecord->get('tmpl_core.discounts_url', '');
		$this->vkUrl = $paramsRecord->get('tmpl_core.vk_url', '');
//		$this->deliveryUrl = $paramsRecord->get('tmpl_core.about_us_url', '');
//		$this->aboutUsUrl = $paramsRecord->get('tmpl_core.about_us_url', '');
//		$this->repairUrl = $paramsRecord->get('tmpl_core.repair_url', '');
		$this->vkIcon = $paramsRecord->get('tmpl_core.vk_icon', '');
		$this->discountCardIcon = $paramsRecord->get('tmpl_core.discount_card_icon', '');
		
		$this->hits = $item->hits;
	}

    private function setField($field, $label = ''): array
    {
        return [
            'label' => $label,
            'value' => $field->raw,
            'result' => $field->result
        ];
    }

	private function setFieldPrice($basePrice, $discount, $label = ''): array
	{
		return [
			'label' => $label,
//			'value' => round($basePrice - ($basePrice * ($discount / 100))),
//			'result' => round($basePrice - ($basePrice * ($discount / 100))) . ' руб.',
			'value' => round($basePrice * $discount),
			'result' => round($basePrice * $discount) . ' руб.',
		];
	}
	
    private function setFieldBoolean($field): bool
	{
		return $field->value === 1;
	}
	
	private function setImage($image): array
	{
		return [
			'url' => JUri::base() . $image->value['image'],
			'width' => $image->params->get('params.thumbs_width', '400') . 'px',
			'height' => $image->params->get('params.thumbs_height', '300') . 'px',
			'result' => $image->result ?? '',
			'real' => true,
		];
	}
	
	private function setVideo($field, $defaultVideo, $title): array
	{
		if (!$field->result){
			return [
				'url' => $defaultVideo,
				'width' => '400px',
				'height' => '300px',
				'result' => '<img src="' . $defaultVideo . '" width="400" height="300" alt="' . $title . '" title="' . $title . '">',
				'real' => false,
			];
		}
		$url = 'https://www.youtube.com/embed/' . trim($field->result);
		return [
			'url' => $url,
			'width' => '400px',
			'height' => '300px',
			'result' => '<div class="uk-cover"><iframe src="' . $url . '" width="400" height="300" allowfullscreen></iframe></div>',
			'real' => true,
		];
	}
	
	private function setGallery($field): array
	{
		$gallery['result'] = $field->result;
		$gallery['baseUrl'] = JUri::base() . JComponentHelper::getParams('com_cobalt')->get('general_upload') . '/' .$field->params->get('params.subfolder');
		$gallery['url'] = [];
		foreach ($field->value as $link) {
			$gallery['url'][] = $link['fullpath'];
		}
		return $gallery;
	}
	
	private function setOpenGraph(object $item):array
	{
		$openGraph = [
			'og:type' => 'article',
			'og:title' => $item->title,
			'og:url' => JRoute::_($item->url, false, 0,1),
			'og:description' => $item->meta_descr,
			'og:site_name' => JFactory::getApplication()->get('sitename'),
			'og:locale' => 'ru_RU',
		];
		
		$images = [];
		$images[] = [
			'og:image' => $this->image['url'],
			'og:image:secure_url' => $this->image['url'],
			'og:image:type' => 'image/jpeg',
			'og:image:width' => $this->image['width'],
			'og:image:height' => $this->image['height'],
		];
		
		if (isset($item->fields_by_id[$this->serviceIds::ID_GALLERY]->raw)) {
			$fieldGallery = $item->fields_by_id[$this->serviceIds::ID_GALLERY];
			$baseUrl = 'images/' . $fieldGallery->params->get('params.subfolder', 'gallery') . '/';
			foreach ($fieldGallery->raw as $picture) {
				$images[] = [
					'og:image' => JUri::base() . $baseUrl . $picture['fullpath'],
					'og:image:secure_url' => JUri::base() . $baseUrl . $picture['fullpath'],
					'og:image:type' => 'image/jpeg',
					'og:image:width' => $picture['width'].'px',
					'og:image:height' => $picture['height'].'px'
				];
			}
		}
		$openGraph += [
			'og:image' => $images
		];
		
		if ($this->video['real']) {
			$ogVideo[] = [
				'og:video' => $this->video['url'],
				'og:video:secure_url' => $this->video['url'],
				'og:video:type' => 'video/mp4',
				'og:video:width' => $this->video['width'],
				'og:video:height' => $this->video['height'],
			];
		}
		else {
			$ogVideo = [];
		}
		$openGraph += [
			'og:video' => $ogVideo
		];
		return $openGraph;
	}

    private function setMetaTitle(object $item): string
    {
        return $item->meta_key !== '' ? $item->meta_key : $item->title.' в '. $this->siteName . ' за ' . $this->priceCurrent['value'] . ' рублей';
    }

    private function setMetaDescription(object $item): string
    {
        return $item->meta_descr !== '' ? $item->meta_descr : $item->title . ' в ' . $this->siteName . ' за ' . $this->priceCurrent['value'] . ' рублей. Описание, фото, рекомендуемые запчасти и аксессуары.';
    }

    private function setTabs(array $fields) :array
	{
		$tabs = [];
		foreach ($this->tabsTemplate as $key => $tab) {
			if ($key === (int) $this->serviceIds::ID_GALLERY) {
				continue;
			}
			if (!isset($fields[$key])) {
				continue;
			}
			if ($fields[$key]->content['total'] === 0) {
				continue;
			}
			$tabs[$key] = [
				'title' => $tab['title'],
				'isActive' => $tab['isActive'],
				'total' => $fields[$key]->content['total'] ?? count($fields[$key]->raw),
				'result' => $fields[$key]->content['html'] ?? $fields[$key]->result,
			];
		}
		if (isset($fields[$this->serviceIds::ID_GALLERY]) && $fields[$this->serviceIds::ID_GALLERY]->result) {
			$tabs[$this->serviceIds::ID_GALLERY] = [
				'title' => $this->tabsTemplate[$this->serviceIds::ID_GALLERY]['title'],
				'isActive' => $this->tabsTemplate[$this->serviceIds::ID_GALLERY]['isActive'],
				'total' => count($fields[$this->serviceIds::ID_GALLERY]->raw),
				'result' => $fields[$this->serviceIds::ID_GALLERY]->result,
			];
		}
		
		return $tabs;
	}
	
	public function renderField($field, $tag = ''): void
	{
		if (!$field['result']){
			return;
		}
		if ($tag !== '') {
			echo  "<$tag>";
		}
		echo "<strong>{$field['label']}:</strong> {$field['result']}";
		if ($tag !== '') {
			echo  "</$tag>";
		}
	}
	
	public function renderEconomy ($price1, $price2): string
	{
		$delta = (int) $price1 - (int) $price2;
//		$percent = number_format(($price1 - $price2 / $price1) * 100, 0);
		return number_format($delta, 0, '.', ' ') . ' руб.';
	}
}
