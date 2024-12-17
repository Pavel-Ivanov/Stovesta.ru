<?php
defined('_JEXEC') or die();
JImport('b0.Product.ProductConfig');
class ProductList
{
	public int $id;
	public array $controls;

	//*** Core
	public string $url;
	public string $title;
	
	//*** Fields
	public array $subtitle;
	
	public array $productCode;
	public array $vendorCode;
	public array $originalCode;
	
	public bool $isSpecial;
	public bool $isOriginal;
	public bool $isByOrder;
	public bool $isGeneral;
    public bool $isHit;
    public array $priceGeneral;
	public array $priceSpecial;
//	public array $priceSimple;
//	public array $priceSilver;
	public array $priceGold;
	public array $priceDelivery;
	public array $priceCurrent;
	
	public array $image;
	public array $imageHit;

	public array $manufacturer;
	public array $mpn;
	
	public array $model;
	public array $generation;
	public array $year;
	public array $body;
	public array $motor;
	public array $drive;
	
	public array $cart;
	
	//*** Meta
	public string $siteName;
	public string $metaTitle;
	public string $metaDescription;
	
	//*** Metrics
	public int $yandex;
	public string $yandexId;
	public string $yandexGoal;
	public int $google;
	public string $googleGoal;
	//*** Social
	public string $hits;
	
	public function __construct($item, $productIds, JRegistry $paramsRecord, JRegistry $paramsApp)
	{
		$fields = $item->fields_by_id;
		
		//*** Core
		$this->id = $item->id;
		$this->url = $item->url;
		$this->title = $item->title;
		//*** Fields
		$this->subtitle = $this->setField($fields[$productIds::ID_SUBTITLE], 'Подзаголовок');
		$this->productCode = $this->setField($fields[$productIds::ID_PRODUCT_CODE], 'Код продукта');
		$this->vendorCode = $this->setField($fields[$productIds::ID_VENDOR_CODE], 'Код производителя');
		$this->originalCode = $this->setField($fields[$productIds::ID_ORIGINAL_CODE], 'Код оригинала');
		
		$this->image = $this->setImage($fields[$productIds::ID_IMAGE]);
		$this->imageHit = $this->setImageHit($fields[$productIds::ID_HIT_IMAGE ?? null]);

		$this->isSpecial = $this->setFieldBoolean($fields[$productIds::ID_IS_SPECIAL]);
		$this->isOriginal = $this->setFieldBoolean($fields[$productIds::ID_IS_ORIGINAL]);
		$this->isByOrder = $this->setFieldBoolean($fields[$productIds::ID_IS_BY_ORDER]);
		$this->isGeneral = !$this->isSpecial && !$this->isByOrder;
        $this->isHit = $this->setFieldBoolean($fields[$productIds::ID_IS_HIT] ?? null);
        $this->priceGeneral = $this->setField($fields[$productIds::ID_PRICE_GENERAL], 'Цена');
		$this->priceSpecial = $this->setField($fields[$productIds::ID_PRICE_SPECIAL], 'Специальная цена');
//		$this->priceSimple = $this->setField($fields[$productIds::ID_PRICE_SIMPLE], 'Стандартный уровень');
//		$this->priceSilver = $this->setField($fields[$productIds::ID_PRICE_SILVER], 'Серебряный уровень');
//		$this->priceGold = $this->setField($fields[$productIds::ID_PRICE_GOLD], 'Золотой уровень');
        $this->priceGold = $this->setFieldPrice(ProductConfig::PRICE_DISCOUNT_ORIGINAL_GOLD, ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_GOLD, 'Золотой уровень');
//		$this->priceDelivery = $this->setField($fields[$productIds::ID_PRICE_DELIVERY], 'Цена при доставке по СПб');
		$this->priceCurrent = $this->isSpecial ? $this->priceSpecial : $this->priceGeneral;
		$this->manufacturer = $this->setField($fields[$productIds::ID_MANUFACTURER], 'Производитель');
		$this->mpn = $this->setField($fields[$productIds::ID_ORIGINAL_CODE]);
		//*** Filters
		$this->model = $this->setField($fields[$productIds::ID_MODEL], 'Модель');
		$this->generation = $this->setField($fields[$productIds::ID_GENERATION], 'Поколение');
		$this->year = $this->setField($fields[$productIds::ID_YEAR], 'Год выпуска');
		$this->body = $this->setField($fields[$productIds::ID_BODY], 'Кузов');
		$this->motor = $this->setField($fields[$productIds::ID_MOTOR], 'Мотор');
		$this->drive = $this->setField($fields[$productIds::ID_DRIVE], 'Привод');
		
		$cart = JFactory::getApplication()->getUserState('cart') ?? [];
		$this->cart = [
			'inCart' => (bool) array_key_exists($this->id, $cart),
			'quantity' => array_key_exists($this->id, $cart) ? (int) $cart[$this->id]['quantity'] : 0,
		];
		
		$this->siteName = JFactory::getApplication()->get('sitename');
		$this->metaTitle = $item->title.' купить в '. $this->siteName . ' за ' . $this->priceCurrent['value'] . ' рублей';
		$this->metaDescription = 'Вы можете купить '. $item->title . ' в магазинах ' . $this->siteName . ' за ' . $this->priceCurrent['value'] . ' рублей. ' .
			$item->title . '- описание, фото, характеристики, аналоги и сопутствующие товары.';
		
		$this->controls = $item->controls;

		$this->yandex = $paramsRecord->get('tmpl_core.yandex', 0);
		$this->yandexId = $paramsApp->get('yandex_id', '');
		$this->yandexGoal = $paramsRecord->get('tmpl_core.yandex_goal', '');
		$this->google = $paramsRecord->get('tmpl_core.google', 0);
		$this->googleGoal = $paramsRecord->get('tmpl_core.google_goal', '');
		
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

    private function setFieldPrice($discountOriginal, $discountNonOriginal, $label = ''): array
    {
        $price = round($this->isOriginal ? $this->priceGeneral['value'] * $discountOriginal : $this->priceGeneral['value'] * $discountNonOriginal);

        return [
            'label' => $label,
            'value' => $price,
            'result' => number_format($price, 0, null, ' ' ) . ' руб.',
        ];
    }

    private function setFieldBoolean($field): bool
	{
//		return $field->value === 1;
        return !is_null($field) && $field->value === 1;

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

    private function setImageHit($image): array
    {
        if (is_null($image)) {
            return [];
        }
        if (!$image->value) {
            return [];
        }
        return [
            'url' => JUri::base() . $image->value['image'],
            'width' => $image->params->get('params.thumbs_width', '400') . 'px',
            'height' => $image->params->get('params.thumbs_height', '300') . 'px',
            'result' => $image->result ?? '',
            'real' => true,
        ];
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
		return number_format($delta, 0, '.', ' ') . ' руб.';
	}
}
