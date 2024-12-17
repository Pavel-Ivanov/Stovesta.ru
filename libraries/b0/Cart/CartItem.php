<?php
defined('_JEXEC') or die();

JImport('b0.Cart.CartItem');
JImport('b0.Cart.CartConfig');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Product.ProductConfig');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

class CartItem
{
	protected const ICON_RUB = '<i class="uk-icon-rub uk-text-muted"></i>';
	
	public $id;
	public string $title;
	public string $subTitle;
	public string $url;
	public string $image;
	public string $productCode;
	public bool $isOriginal;
	public bool $isSpecial;
	public int $priceGeneral;
    public int $priceSpecial;
    public int $priceDelivery;
    public int $priceDeliveryRegions;
    public int $priceDeliveryMyself;
//	public $costDelivery;
	public int $quantity;
	public int $priceCurrent;
	public int $amountCurrent;
    public array $availability = [];
    public int $availabilityTotal = 0;

	public function __construct($item)
	{
		// Поля в $item -> id, title, alias, fields
		$fields = json_decode($item->fields, TRUE);
		$cartSession = JFactory::getApplication()->getUserState('cart');
		$this->id = $item->id;
		$this->title = $item->title;
		$this->subTitle = $this->setSubTitle($fields, $item->type_id);
		$this->url = $this->setUrl($item);
		$this->image = $this->setImage($fields, $item->type_id, $item->id);
		$this->productCode =  $this->setProductCode($fields, $item->type_id);
		$this->isOriginal = $this->setIsOriginal($fields, $item->type_id);
		$this->isSpecial = $this->setIsSpecial($fields, $item->type_id);
        $this->priceGeneral = $this->setPriceGeneral($fields, $item->type_id);
        $this->priceSpecial = $this->setPriceSpecial($fields, $item->type_id);
        $this->priceDelivery = $this->setPrice(ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY, ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY);
        $this->priceDeliveryRegions = $this->setPrice(ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY_REGIONS, ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY_REGIONS);
        $this->priceDeliveryMyself = $this->setPrice(ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY_MYSELF, ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY_MYSELF);
		$this->quantity = $cartSession[$item->id]['quantity'];
		$this->priceCurrent = 0;
		$this->amountCurrent = 0;
        $this->availability = $this->setAvailability($fields, $item->type_id);
        $this->availabilityTotal = $this->setAvailabilityTotal();
	}
	
	private function setSubTitle($fields, $typeId):string
	{
        if ($typeId == AccessoryIds::ID_TYPE) {
			return $fields[AccessoryIds::ID_SUBTITLE];
        }
		if ($typeId == SparepartIds::ID_TYPE) {
			return $fields[SparepartIds::ID_SUBTITLE];
		}
		return '';
	}
	
	private function setUrl($item):string
	{
		if ($item->type_id == SparepartIds::ID_TYPE) {
			return JRoute::_('/spareparts/item/'.$item->id.'-'.$item->alias);
		}
		if ($item->type_id == AccessoryIds::ID_TYPE) {
			return JRoute::_('/accessories/item/'.$item->id.'-'.$item->alias);
		}
		return '';
	}
	
	private function setImage($fields, $typeId, $itemId):string
	{
		if ($typeId == SparepartIds::ID_TYPE) {
			return $fields[SparepartIds::ID_IMAGE]['image'] ?? CobaltApi::getField(SparepartIds::ID_IMAGE, $itemId)->params->get('params.default_img');
		}
		if ($typeId == AccessoryIds::ID_TYPE) {
			return $fields[AccessoryIds::ID_IMAGE]['image'] ?? CobaltApi::getField(AccessoryIds::ID_IMAGE, $itemId)->params->get('params.default_img');
		}
		return '';
	}
	
	private function setProductCode($fields, $typeId):string
	{
		if ($typeId == SparepartIds::ID_TYPE) {
			return $fields[SparepartIds::ID_PRODUCT_CODE];
		}
		if ($typeId == AccessoryIds::ID_TYPE) {
			return $fields[AccessoryIds::ID_PRODUCT_CODE];
		}
		return '';
	}
	
	private function setIsOriginal($fields, $typeId):bool
	{
		if ($typeId == SparepartIds::ID_TYPE) {
			return isset($fields[SparepartIds::ID_IS_ORIGINAL]) && ($fields[SparepartIds::ID_IS_ORIGINAL] == 1);
		}
		if ($typeId == AccessoryIds::ID_TYPE) {
			return isset($fields[AccessoryIds::ID_IS_ORIGINAL]) && ($fields[AccessoryIds::ID_IS_ORIGINAL] == 1);
		}
		return false;
	}

	private function setIsSpecial($fields, $typeId):bool
	{
		if ($typeId == SparepartIds::ID_TYPE) {
			return isset($fields[SparepartIds::ID_IS_SPECIAL]) && ($fields[SparepartIds::ID_IS_SPECIAL] == 1);
		}
		if ($typeId == AccessoryIds::ID_TYPE) {
			return isset($fields[AccessoryIds::ID_IS_SPECIAL]) && ($fields[AccessoryIds::ID_IS_SPECIAL] == 1);
		}
		return false;
	}

	private function setPriceGeneral($fields, $typeId):int
	{
		if ($typeId == SparepartIds::ID_TYPE) {
			return (int) $fields[SparepartIds::ID_PRICE_GENERAL];
		}
		if ($typeId == AccessoryIds::ID_TYPE) {
			return (int) $fields[AccessoryIds::ID_PRICE_GENERAL];
		}
		return 0;
	}

    private function setPriceSpecial($fields, $typeId):int
    {
        if ($typeId == SparepartIds::ID_TYPE) {
            return (int) $fields[SparepartIds::ID_PRICE_SPECIAL];
        }
        if ($typeId == AccessoryIds::ID_TYPE) {
            return (int) $fields[AccessoryIds::ID_PRICE_SPECIAL];
        }
        return 0;
    }

	private function setPrice($discountOriginal, $discountNonOriginal):int
	{
        if ($this->isOriginal) {
            return round($this->priceGeneral * $discountOriginal);
        }
        else {
            return round($this->priceGeneral * $discountNonOriginal);
        }
	}

    private function setAvailability ($fields, $typeId):array
    {
        if ($typeId == SparepartIds::ID_TYPE) {
            return [
                CartConfig::CART_ID_GAGARINA => $fields[SparepartIds::ID_SEDOVA],
                CartConfig::CART_ID_KHIMIKOV => $fields[SparepartIds::ID_KHIMIKOV],
                CartConfig::CART_ID_KULTURY => $fields[SparepartIds::ID_KULTURY],
                CartConfig::CART_ID_PLANERNAYA => $fields[SparepartIds::ID_PLANERNAYA],
                CartConfig::CART_ID_ZHUKOVA => $fields[SparepartIds::ID_ZHUKOVA],
            ];
        }
        if ($typeId == AccessoryIds::ID_TYPE) {
            return [
                CartConfig::CART_ID_GAGARINA => $fields[AccessoryIds::ID_SEDOVA],
                CartConfig::CART_ID_KHIMIKOV => $fields[AccessoryIds::ID_KHIMIKOV],
                CartConfig::CART_ID_KULTURY => $fields[AccessoryIds::ID_KULTURY],
                CartConfig::CART_ID_PLANERNAYA => $fields[AccessoryIds::ID_PLANERNAYA],
                CartConfig::CART_ID_ZHUKOVA => $fields[AccessoryIds::ID_ZHUKOVA],
            ];

        }
        return [];
    }

    private function setAvailabilityTotal():int
    {
        return array_sum($this->availability);
    }

        /*	private function setPriceDelivery($fields, $typeId):int
            {
                if ($this->isOriginal) {
                    return round($this->priceGeneral * ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY);
                }
                else {
                    return round($this->priceGeneral * ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY);
                }
            }*/

/*	private function setPriceDeliveryRegions($fields, $typeId):int
	{
        if ($this->isOriginal) {
            return round($this->priceGeneral * ProductConfig::PRICE_DISCOUNT_ORIGINAL_DELIVERY_REGIONS);
        }
        else {
            return round($this->priceGeneral * ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_DELIVERY_REGIONS);
        }
    }*/

	public function renderProductCode() {
		if (mb_strlen($this->productCode) == 0) {
			return;
		}
		echo '<p><strong>Код товара: </strong>' . $this->productCode;
		if ($this->isSpecial) {
			echo ' / <span class="uk-text-danger" data-uk-tooltip title="Скидки по дисконтным картам не действуют">спецпредложение</span></p>';
		}
	}
	
	public function renderPrice($price) {
		echo number_format($price, 0, '.', ' ') . ' ' . CartItem::ICON_RUB;
	}
}
