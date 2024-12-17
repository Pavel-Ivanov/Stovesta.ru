<?php
defined('_JEXEC') or die();

JImport('b0.Feed.FeedConfig');
JImport('b0.Product.ProductConfig');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');

class FeedOffer
{
	private $id;
	private $sectionId;
	private $categoryId;
	private $fields;
	private string $alias;
	private string $name;
	private string $description;
	private bool $isSpecial;
	private bool $isOriginal;
	private int $priceGeneral;
	private int $priceSpecial;
	private int $priceDelivery;
	private int $priceDiscount;
	private string $manufacturer;
	private string $vendor;
	private string $vendorCode;
	private string $shopSku;
	private string $country;
	private ?string $imageUrl;
	private array $params;
	private int $availability;
	
	public function __construct(object $item, $categories)
	{
		$this->id = $item->id;
		$this->sectionId = $item->section_id;
		$this->categoryId = $this->getCategoryId($item, $categories);
		$this->fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
		$this->alias = $item->alias;
		$this->name = $this->getName($item);
		$this->description = $this->getDescription($item);
		$this->isSpecial = $this->getIsSpecial();
		$this->isOriginal = $this->getIsOriginal();
		$this->priceGeneral = $this->getPriceGeneral();
		$this->priceSpecial = $this->getPriceSpecial();
		$this->priceDelivery = $this->getPriceDelivery();
		$this->priceDiscount = $this->getPriceDiscount();
		$this->manufacturer = $this->getManufacturer();
		$this->vendor = $this->getVendor();
		$this->vendorCode = $this->getVendorCode();
		$this->shopSku = $this->getShopSku();
		$this->country = $this->getCountry();
		$this->imageUrl = $this->getImageUrl();
		$this->params = $this->getParams();
//		$this->availability = $this->getAvailability();
		$this->availability = 0;
	}
	
	private function getCategoryId(object $item, array $categories)
	{
		$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
		// Проверяем наличие установленной категории
		// если не установлена, возвращает 1 - id самой верхней категории
		switch ($item->section_id) {
			case SparepartIds::ID_SECTION:
				if (!isset($fields[SparepartIds::ID_YM_CATEGORY]) || empty($fields[SparepartIds::ID_YM_CATEGORY])) {
					return 1;
				}
				break;
			case AccessoryIds::ID_SECTION:
				if (!isset($fields[AccessoryIds::ID_YM_CATEGORY]) || empty($fields[AccessoryIds::ID_YM_CATEGORY])) {
					return 1;
				}
				break;
			default:
				return false;
		}
		
		// Получаем название категории
		switch ($item->section_id) {
			case SparepartIds::ID_SECTION:
				$cats = array_shift($fields[SparepartIds::ID_YM_CATEGORY]);
				$categoryName = end($cats);
				break;
			case AccessoryIds::ID_SECTION:
				$cats = array_shift($fields[AccessoryIds::ID_YM_CATEGORY]);
				$categoryName = end($cats);
				break;
			default:
				return 'Авто';
		}
		// Ищем по названию категории
		if (in_array($categoryName, array_column($categories, 'name'), true)) {
			$cat_id = array_search($categoryName, array_column($categories, 'name', 'id'), true);
		}
		else {
			$cat_id = 1;
		}
		return $cat_id;
	}
	
	private function getName($item): string
	{
/*		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				if (isset($this->fields[SparepartIds::ID_YM_TITLE]) && $this->fields[SparepartIds::ID_YM_TITLE] !== '') {
					return $this->fields[SparepartIds::ID_YM_TITLE];
				}
				return $item->title;
			case AccessoryIds::ID_SECTION:
				if (isset($this->fields[AccessoryIds::ID_YM_TITLE]) && $this->fields[AccessoryIds::ID_YM_TITLE] !== '') {
					return $this->fields[AccessoryIds::ID_YM_TITLE];
				}
				return $item->title;
			default:
				return $item->title;
		}*/
		return $item->title;
	}
	
	private function getDescription($item): string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				$description = '<![CDATA[';
				$description .= '<p>' . $item->title . ' для ' . implode(', ', $this->fields[SparepartIds::ID_MODEL]) . '.</p>';
				$description .= isset($this->fields[SparepartIds::ID_DESCRIPTION]) ? str_ireplace('&nbsp;','',$this->fields[SparepartIds::ID_DESCRIPTION]) : '';
				$description .= ']]>';
				return $description;
			case AccessoryIds::ID_SECTION:
				$description = '<![CDATA[';
				$description .= '<p>' . $item->title . ' для ' . implode(', ', $this->fields[AccessoryIds::ID_MODEL]) . '.</p>';
				$description .= isset($this->fields[AccessoryIds::ID_DESCRIPTION]) ? str_ireplace('&nbsp;','',$this->fields[AccessoryIds::ID_DESCRIPTION]) : '';
				$description .= ']]>';
				return $description;
			default:
				return '';
		}
	}
	
	private function getPriceGeneral(): int
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return (int) $this->fields[SparepartIds::ID_PRICE_GENERAL];
			case AccessoryIds::ID_SECTION:
				return (int) $this->fields[AccessoryIds::ID_PRICE_GENERAL];
			default:
				return '';
		}
	}

	private function getPriceSpecial(): int
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return (int) $this->fields[SparepartIds::ID_PRICE_SPECIAL];
			case AccessoryIds::ID_SECTION:
				return (int) $this->fields[AccessoryIds::ID_PRICE_SPECIAL];
			default:
				return '';
		}
	}
	
	private function getPriceDelivery(): string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
                if ($this->isOriginal) {
					return (int) round($this->fields[SparepartIds::ID_PRICE_GENERAL] * ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY);
				}
				else {
					return (int) round($this->fields[SparepartIds::ID_PRICE_GENERAL] * ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY);
				}
			case AccessoryIds::ID_SECTION:
                if ($this->isOriginal) {
					return (int) round($this->fields[AccessoryIds::ID_PRICE_GENERAL] * ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY);
				}
				else {
					return (int) round($this->fields[AccessoryIds::ID_PRICE_GENERAL] * ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY);
				}
			default:
				return '';
		}
	}
	
	private function getIsSpecial():bool
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return $this->fields[SparepartIds::ID_IS_SPECIAL] === 1;
			case AccessoryIds::ID_SECTION:
				return $this->fields[AccessoryIds::ID_IS_SPECIAL] === 1;
			default:
				return false;
		}
	}

	private function getIsOriginal():bool
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return $this->fields[SparepartIds::ID_IS_ORIGINAL] === 1;
			case AccessoryIds::ID_SECTION:
				return $this->fields[AccessoryIds::ID_IS_ORIGINAL] === 1;
			default:
				return false;
		}
	}

	private function getPriceDiscount():int
	{
		if ($this->priceGeneral === 0) {
			return 0;
		}
		return (int)(($this->priceGeneral - $this->priceSpecial) * 100 / $this->priceGeneral);
	}
	
	private function getManufacturer() :string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return $this->fields[SparepartIds::ID_MANUFACTURER][0] ?? '';
			case AccessoryIds::ID_SECTION:
				return $this->fields[AccessoryIds::ID_MANUFACTURER][0] ?? '';
			default:
				return '';
		}
	}
	
	private function getVendor() :string
	{
		$pos = strpos($this->manufacturer, '/');
		$vendor = trim(substr($this->manufacturer, 0, $pos));
		$country = trim(substr($this->manufacturer, ++$pos));
		if (!$vendor) {$vendor = $country;}
		return $vendor;
	}
	
	private function getVendorCode() :string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return $this->fields[SparepartIds::ID_VENDOR_CODE];
			case AccessoryIds::ID_SECTION:
				return $this->fields[AccessoryIds::ID_VENDOR_CODE];
			default:
				return '';
		}
	}
	
	private function getShopSku() :string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return $this->fields[SparepartIds::ID_PRODUCT_CODE];
			case AccessoryIds::ID_SECTION:
				return $this->fields[AccessoryIds::ID_PRODUCT_CODE];
			default:
				return '';
		}
	}
	
	private function getCountry() :string
	{
		$pos = strpos($this->manufacturer, '/');
		return trim(substr($this->manufacturer, ++$pos));
	}
	
	private function getImageUrl() : ?string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
/*				if (isset($this->fields[SparepartIds::ID_YM_IMAGE]['image'])) {
					return $this->fields[SparepartIds::ID_YM_IMAGE]['image'];
				}*/
				if (isset($this->fields[SparepartIds::ID_IMAGE]['image'])) {
					return $this->fields[SparepartIds::ID_IMAGE]['image'];
				}
				else {
					return FeedConfig::FEED_URL_NO_IMAGE;
				}
			case AccessoryIds::ID_SECTION:
/*				if (isset($this->fields[AccessoryIds::ID_YM_IMAGE]['image'])) {
					return $this->fields[AccessoryIds::ID_YM_IMAGE]['image'];
				}*/
				if (isset($this->fields[AccessoryIds::ID_IMAGE]['image'])) {
					return $this->fields[AccessoryIds::ID_IMAGE]['image'];
				}
				else {
					return FeedConfig::FEED_URL_NO_IMAGE;
				}
			default:
				return '';
		}
	}
	
	private function getParams():array
	{
		$params = [];
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				$params['models'] = $this->fields[SparepartIds::ID_MODEL];
				$params['motors'] = $this->fields[SparepartIds::ID_MOTOR];
				$params['years'] = $this->fields[SparepartIds::ID_YEAR];
				return $params;
			case AccessoryIds::ID_SECTION:
				$params['models'] = $this->fields[AccessoryIds::ID_MODEL];
				$params['motors'] = $this->fields[AccessoryIds::ID_MOTOR];
				$params['years'] = $this->fields[AccessoryIds::ID_YEAR];
				return $params;
			default:
				return $params;
		}
	}
	
	private function getAvailability(): int
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return isset($this->fields[SparepartIds::ID_KHIMIKOV]) ? (int) $this->fields[SparepartIds::ID_KHIMIKOV] : 0;
			case AccessoryIds::ID_SECTION:
				return isset($this->fields[AccessoryIds::ID_KHIMIKOV]) ? (int) $this->fields[AccessoryIds::ID_KHIMIKOV] : 0;
			default:
				return 0;
		}
	}
	
	public function renderOffer() :string
	{
		$content = '<offer id="' . $this->shopSku . '" available="true">' . "\n";
		$content .= $this->renderUrl();
		$content .=	$this->renderPrice();
		$content .= $this->renderOldPrice();
//		$content .= $this->renderSalesNotes();
//		$content .= $this->renderAge();
		$content .= $this->renderCurrencyId();
		$content .= $this->renderCategoryId();
		$content .= $this->renderPicture();
		$content .= $this->renderStore();
		$content .= $this->renderPickup();
		$content .= $this->renderDelivery();
//		$content .= $this->renderDeliveryOptions();
		$content .= $this->renderNameYml();
		$content .= $this->renderDescription();
		$content .= $this->renderVendor();
//		$content .= $this->renderVendorCode();
		$content .= $this->renderShopSku();
//		$content .= $this->renderVendorModel();
		$content .= $this->renderManufacturerWarranty();
		$content .= $this->renderCountryOfOrigin();
//		$content .= $this->renderParams();
/*        if ($mode === FeedConfig::FEED_MODE_MARKET || $mode === FeedConfig::FEED_MODE_OFFERS_ONLY) {
            $content .= $this->renderCount();
        }*/
		$content .= '</offer>' . "\n";
		return $content;
	}
	
	public function renderOfferFbs() :string
	{
		$content = '<offer id="' . $this->shopSku . '" available="true">' . "\n";
		$content .= $this->renderUrl();
		$content .=	$this->renderPrice();
		$content .= $this->renderOldPrice();
//		$content .= $this->renderSalesNotes();
//		$content .= $this->renderAge();
		$content .= $this->renderCurrencyId();
		$content .= $this->renderCategoryId();
		$content .= $this->renderPicture();
		$content .= $this->renderStore();
		$content .= $this->renderPickup();
		$content .= $this->renderDelivery();
//		$content .= $this->renderDeliveryOptions();
		$content .= $this->renderNameYml();
		$content .= $this->renderDescription();
		$content .= $this->renderVendor();
//		$content .= $this->renderVendorCode();
		$content .= $this->renderShopSku();
//		$content .= $this->renderVendorModel();
		$content .= $this->renderManufacturerWarranty();
		$content .= $this->renderCountryOfOrigin();
//		$content .= $this->renderParams();
		$content .= $this->renderCount();
		$content .= '</offer>' . "\n";
		return $content;
	}
	
	private  function renderUrl():string
	{
		switch ($this->sectionId) {
			case SparepartIds::ID_SECTION:
				return '<url>' . FeedConfig::FEED_URL_SPAREPARTS . $this->id . '-' . $this->alias . '</url>' . "\n";
			case AccessoryIds::ID_SECTION:
				return '<url>' . FeedConfig::FEED_URL_ACCESSORIES . $this->id . '-' . $this->alias . '</url>' . "\n";
			default:
				return '';
		}
	}

	private  function renderPrice():string
	{
		$price = $this->isSpecial ? $this->priceSpecial : $this->priceGeneral;
//		$price = $this->isSpecial && $this->priceDiscount >= 5 ? $this->priceSpecial : $this->priceGeneral;
		return '<price>'.$price.'.00</price>' . "\n";
	}
	
	private  function renderOldPrice():string
	{
		if (!$this->isSpecial) {
			return '';
		}
		if ($this->priceDiscount <= 5) {
			return '';
		}
		return '<oldprice>'.$this->priceGeneral.'.00</oldprice>' . "\n";
	}
	
	private  function renderSalesNotes(): string
	{
		$price = ($this->isSpecial) ? $this->priceSpecial : $this->priceGeneral;
		if ($price >= FeedConfig::FEED_LIMIT_DELIVERY_CITY) {
			return '';
		}
		return '<sales_notes>' . FeedConfig::FEED_SALES_NOTES_DELIVERY . '</sales_notes>' . "\n";
	}
	
	private  function renderAge(): string
	{
		return '<age>0</age>' . "\n";
	}
	
	private  function renderCurrencyId(): string
	{
		return '<currencyId>RUR</currencyId>' . "\n";
	}
	
	private  function renderCategoryId(): string
	{
		return '<categoryId>'.$this->categoryId.'</categoryId>' . "\n";
	}
	
	private  function renderPicture(): string
	{
		return '<picture>'.FeedConfig::FEED_URL.'/'.$this->imageUrl.'</picture>' . "\n";
	}
	
	private  function renderStore(): string
	{
		return '<store>true</store>' . "\n";
	}
	
	private  function renderPickup(): string
	{
		return '<pickup>true</pickup>' . "\n";
	}
	
	private function renderDelivery(): string
	{
		return '<delivery>false</delivery>' . "\n";
	}
	
	private function renderDeliveryOptions(): string
	{
		$price = ($this->isSpecial) ? $this->priceSpecial : $this->priceDelivery;
//		if ($price >= FeedConfig::FEED_LIMIT_DELIVERY_CITY) {
		if ($price >= FeedConfig::FEED_LIMIT_DELIVERY_SATELLITES) {
			return '';
		}
		$str = '<delivery-options>' . "\n";
		$str .= '<option cost="'.FeedConfig::FEED_PRICE_DELIVERY_SATELLITES.'" days="'.FeedConfig::FEED_DELIVERY_OPTIONS[0]['days'].'"/>' . "\n";
		$str .= '</delivery-options>' . "\n";
		return $str;
	}
	
	private  function renderNameYml(): string
	{
		return '<name>'.$this->name.'</name>' . "\n";
	}
	
	private  function renderDescription(): string
	{
		return '<description>'.$this->description.'</description>' . "\n";
	}
	
	private  function renderVendor():string
	{
		return '<vendor>'.$this->vendor.'</vendor>' . "\n";
	}
	
	private  function renderVendorCode():string
	{
		if ($this->vendorCode === '') {
			return '';
		}
		return '<vendorCode>'.$this->vendorCode.'</vendorCode>' . "\n";
	}
	
	private  function renderShopSku():string
	{
		if ($this->shopSku === '') {
			return '';
		}
		return '<shop-sku>'.$this->shopSku.'</shop-sku>' . "\n";
	}
	
	private  function renderVendorModel():string
	{
		if ($this->vendorCode === '') {
			return '';
		}
		return '<model>'.$this->vendorCode.'</model>' . "\n";
	}
	
	private  function renderManufacturerWarranty():string
	{
		return '<manufacturer_warranty>true</manufacturer_warranty>' . "\n";
	}
	
	private  function renderCountryOfOrigin():string
	{
		return '<country_of_origin>'.$this->country.'</country_of_origin>' . "\n";
	}
	
	private function renderParams():string
	{
		$paramsStr = '';
		if (isset($this->params['models'])){
			foreach ($this->params['models'] as $model){
				$paramsStr .= '<param name="Марка автомобиля">' . $model . '</param>' . "\n";
			}
		}
		if (isset($this->params['motors'])){
			foreach ($this->params['motors'] as $motor){
				$paramsStr .= '<param name="Мотор автомобиля">' . $motor . '</param>' . "\n";
			}
		}
		if (isset($this->params['years'])){
			foreach ($this->params['years'] as $year){
				$paramsStr .= '<param name="Год выпуска автомобиля">' . $year . '</param>' . "\n";
			}
		}
		return $paramsStr;
	}
	
	private function renderCount():string
	{
		return '<count>'.$this->availability.'</count>' . "\n";
	}
}
