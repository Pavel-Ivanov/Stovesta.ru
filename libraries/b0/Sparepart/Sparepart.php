<?php
defined('_JEXEC') or die;

class Sparepart extends Item
{
	//***
	private const KEY_SUBTITLE = 'k988d70d9504e7cd4df51b5bd9cd6c766';   // 1
	private const KEY_SEARCH_SYNONYMS = 'kfbd541e87607957755493c8e6540b688';    // 2
	private const KEY_MANUFACTURER = 'k96963673586d06ae638dcbe75144f47b';    // 7
	private const KEY_IMAGE = 'k776c43fe8b8ce0684ddb973bd8e6d3f9';    // 3
	//***
	private const KEY_PRODUCT_CODE = 'kad3298079b8c8638bae4d565425ec731';    // 4
	private const KEY_VENDOR_CODE = 'k08552816784e7b350f71999a37920289';    // 5
	private const KEY_ORIGINAL_CODE = 'kd92ebe423cc5c08498c292b90acdcaea';    // 6
	//*** Prices
	private const KEY_PRICE_GENERAL = 'k54972c2f2fe6a749b8cb0179f114f394'; // 8
	private const KEY_PRICE_SIMPLE = 'kef83c95a5953a3ba446b307ea16b7312'; // 46
	private const KEY_PRICE_SILVER = 'kb3dadc650efa54930ccdec4830f62453'; // 63
	private const KEY_PRICE_GOLD = 'k32d54e1bd886b6b60dd4d1d8f243e9a7'; // 64
	private const KEY_PRICE_DELIVERY = 'kd877ba32ad9a285a50c1661349513114'; // 65
	private const KEY_IS_SPECIAL = 'kf7fb9e992777ebd5d3b9a096a9c7c817'; // 47
	private const KEY_PRICE_SPECIAL = 'k3c0c68264e36441bfabcb303b6cd6e11';  // 9
	private const KEY_IS_ORIGINAL = 'k14d0da63917175ceffa23f25925b2848'; // 10
	private const KEY_IS_BY_ORDER = 'k1acb923dc0866cb1d0c666c98748455f'; // 11
	//*** Availability
	private const KEY_SEDOVA = 'k8e9446f94299cf74ea82d0ceb919b3b2';  // 117
	private const KEY_KHIMIKOV = '';  //
	private const KEY_VOZROZHDENIA = '';  //
	private const KEY_SHKOLNAYA = 'k650e382ada751515b8c233f0e947e7dc';  // 118
	private const KEY_VYBORGSKOYE = '';  //
	//***
	private const KEY_CHARACTERISTICS = 'k7fceae06235f75f76e1f0fa46e6a225f';    // 21
	private const KEY_DESCRIPTION = 'kbb140b6ce8c9cadcde8c87772e8282d6';    // 20
	private const KEY_GALLERY = 'k4374b93371d715b0a375d410ab98a316';    // 133
	private const KEY_VIDEO = 'k64d2fb19fe6dc39d3edd4e0baa55e786';  // 113
	//*** Filters
	private const KEY_CATEGORY = 'k08005f0bec31df8ff08571ea0c969280';
	private const KEY_MODEL = 'k2c2d3a150ee055e8a427be13d2db1eb5';  // 13
	private const KEY_YEAR = 'k188db5d9655bc4fcbb8bd1365184885e';   // 14
	private const KEY_MOTOR = 'kdc66b066adb35de6d0e8f7a34554230e';  // 15
	private const KEY_DRIVE = 'k608a63da4652b340b1e8df15b38c955d';  // 134
	//*** Relations
	private const KEY_ANALOGS = 'k1e05a0ebfe45be5796cd4daf1e6901c8'; // 16
	private const KEY_ASSOCIATED = 'ked71ecec556ee2b59a3f8f9cdd228be0'; // 32
	private const KEY_WORKS = 'k8be20cfae46409e02aa4f3d1c5504a43'; // 42
	private const KEY_FOR_READING = ''; //
	private const KEY_MAINTENANCE = 'k5ecce2ebb38285c7493682f305c88d29'; // 60

	// Microdata
	public $microData;
	// Meta
	public $metaTitle;
	public $metaDescription;
	//*** Fields
	public $subtitle;
	public $manufacturer;
	public $image;
	//*** Codes
	public $productCode;
	public $vendorCode;
	public $originalCode;
	//*** Prices
	public $priceGeneral;
	public $priceSimple;
	public $priceSilver;
	public $priceGold;
	public $priceDelivery;
	public $isSpecial;
	public $priceSpecial;
	public $isOriginal;
	public $isByOrder;

	public $description;    // 51
	public $gallery;    // 122
	public $video;    // 123

	public $itemToCart;

	public $openGraph;

	public $presence;

	public $models;


	public function __construct($item, $user, $microdata)
	{
		parent::__construct($item, $user);
		//$this->title = $this->setTitle($item);
		$this->microData = $microdata;
		//*** Meta
		$this->metaTitle = $this->setMetaTitle($item);
		$this->metaDescription = $this->setMetaDescription($item);
		//***
		$this->subtitle = $item->fields_by_key[Sparepart::KEY_SUBTITLE]->result ?? '';
		//$this->subtitle = $this->setField($item->fields_by_key[Sparepart::KEY_SUBTITLE] ?? null, $user);
		$this->manufacturer = $item->fields_by_key[Sparepart::KEY_MANUFACTURER]->result ?? '';
		$this->image = [
			'url'=> isset($item->fields_by_key[Sparepart::KEY_IMAGE]) ? JUri::base() . $item->fields_by_key[Sparepart::KEY_IMAGE]->value['image'] : '',
			'result' => $item->fields_by_key[Sparepart::KEY_IMAGE]->result ?? null
		];
		//*** Codes
		$this->productCode = $item->fields_by_key[Sparepart::KEY_PRODUCT_CODE]->result ?? '';
		$this->vendorCode = $item->fields_by_key[Sparepart::KEY_VENDOR_CODE]->value ?? '';
		$this->originalCode = $item->fields_by_key[Sparepart::KEY_ORIGINAL_CODE]->value ?? '';
		//$this->originalCode = $this->setField($item->fields_by_key[Sparepart::KEY_ORIGINAL_CODE] ?? null, $user->getAuthorisedViewLevels());
		//b0debug($item->fields_by_key[Sparepart::KEY_MODEL]->value);
		//b0debug($item->fields_by_key[Sparepart::KEY_MODEL]->result);
//		b0debug($item->fields_by_key[Sparepart::KEY_ORIGINAL_CODE]->value);
//		b0debug($item->fields_by_key[Sparepart::KEY_ORIGINAL_CODE]->result);
//		b0debug($item->fields_by_key[Sparepart::KEY_ORIGINAL_CODE]);
		//*** Prices
		$this->priceGeneral = $item->fields_by_key[Sparepart::KEY_PRICE_GENERAL]->value ?? 0;
		$this->priceSimple = $item->fields_by_key[Sparepart::KEY_PRICE_SIMPLE]->value ?? 0;
		$this->priceSilver = $item->fields_by_key[Sparepart::KEY_PRICE_SILVER]->value ?? 0;
		$this->priceGold = $item->fields_by_key[Sparepart::KEY_PRICE_GOLD]->value ?? 0;
		$this->priceDelivery = $item->fields_by_key[Sparepart::KEY_PRICE_DELIVERY]->value ?? 0;
		$this->isSpecial = ($item->fields_by_key[Sparepart::KEY_IS_SPECIAL]->value == '1') ? true : false;
		$this->priceSpecial = $item->fields_by_key[Sparepart::KEY_PRICE_SPECIAL]->value ?? 0;
		$this->isByOrder = ($item->fields_by_key[Sparepart::KEY_IS_BY_ORDER]->value == '1') ? true : false;
		$this->isOriginal = ($item->fields_by_key[Sparepart::KEY_IS_ORIGINAL]->value == '1') ? true : false;

		$this->models = $item->fields_by_key[Sparepart::KEY_MODEL]->result ?? '';
		$this->years = $item->fields_by_key[Sparepart::KEY_YEAR]->result ?? '';
		$this->motors = $item->fields_by_key[Sparepart::KEY_MOTOR]->result ?? '';
		$this->drives = $item->fields_by_key[Sparepart::KEY_DRIVE]->result ?? '';

//		$this->presence = new stdClass();
		$this->presence = new JRegistry(['label' => $item->fields_by_key[Sparepart::KEY_PRODUCT_CODE]->label,
		                                 'value' => $item->fields_by_key[Sparepart::KEY_PRODUCT_CODE]->value,
		                                 'result' => $item->fields_by_key[Sparepart::KEY_PRODUCT_CODE]->result]);

		$this->itemToCart = $this->setItemToCart($item);

		//$authorised = JFactory::getUser()->getAuthorisedViewLevels();
		$this->openGraph = [
			'og:type' => 'article',
			'og:title' => $this->title,
			'og:image' => $this->image['url'],
			'og:image:secure_url' => $this->image['url'],
			'og:image:type' => 'image/jpeg',
			'og:image:width' => '400',
			'og:image:height' => '300',
			'og:url' => $this->url,
			'og:description' => $this->metaDescription,
			'og:site_name' => 'StoVesta',
			'og:locale' => 'ru_RU',
		];
		if ($this->video['result']) {
			$this->openGraph += [
				'og:video' => $this->video['url'],
				'og:video:secure_url' => $this->video['url'],
				'og:video:type' => 'video/mp4',
				'og:video:width' => '640',
				'og:video:height' => '480',
			];
		}

	}

	private function setTitle($item): string
	{
		return $item->title;
	}

	private function setMetaTitle($item): string
	{
		return $item->title .' купить в СТО Веста СПб';
	}

	private function setMetaDescription($item): string
	{
		return 'Купить '. lcfirst($item->title) . ' по доступной цене в магазинах Ларгус-Шоп СПб. ' .
			$item->title . '- описание, фото, характеристики, аналоги, сопутствующие товары и отзывы покупателей.';
	}

	private function setItemToCart($item)
	{
		$cartItem = [
			'typeId' => $item->type_id,
			'title' => $item->title,
			'subTitle' => $item->fields_by_id[1]->result ?? '',
			'url' => JRoute::_('/spareparts/item/'.$item->id.'-'.$item->alias),
			'image' => $item->fields[3]['image'] ?? '',
			'code' => $item->fields_by_id[4]->result ?? '',
			'isSpecial' => ($item->fields_by_id[47]->value == 1) ? 1 : 0,
			'price' => $item->fields_by_id[65]->value ?? ''
		];
		return serialize($cartItem);

	}

	public function renderImage() {
		echo $this->image['result'];
	}

	public function renderModels() {
		if (mb_strlen($this->models) == 0) {
			return;
		}
		echo '<p class="ls-sub-title">для ' . $this->models . '</p>';
	}

	public function renderSubtitle() {
		if (mb_strlen($this->subtitle) == 0) {
			return;
		}
		echo '<p class="uk-article-lead">' . $this->subtitle . '</p>';
	}

	public function renderProductCode() {
		if (mb_strlen($this->productCode) == 0) {
			return;
		}
		echo '<p class="uk-float-right">';
		echo '<strong>Код товара: </strong>' . $this->productCode;
		echo '</p>';
	}

	protected function getPrice($price) {
		return number_format(round($price, -1), 0, '.', ' ') . ' RUB';
	}

	public function getPriseSimple() {
		return $this->getPrice($this->priceSimple);
	}

	public function getPriseSilver() {
		return $this->getPrice($this->priceSilver);
	}

	public function getPriseGold() {
		return $this->getPrice($this->priceGold);
	}

}