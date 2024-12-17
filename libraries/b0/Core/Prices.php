<?php
defined('_JEXEC') or die;
JImport('b0.Core.PricesKeys');

trait Prices
{
	public $priceGeneral;
	public $priceSimple;
	public $priceSilver;
	public $priceGold;
	public $priceSpecial;
	public $priceDelivery;
	public $priceFirstVisit;
	public $isSpecial;
	public $isOriginal;
	public $isByOrder;
	private $Rub = '<i class="uk-icon-rub uk-text-muted uk-margin-left"></i>';

	private function setPriceGeneral($fields)
	{
		$this->priceGeneral = $fields[PricesKeys::PRICE_GENERAL_KEY]->value ?? 0;
	}

	private function setPriceSimple($fields)
	{
		$this->priceSimple = $fields[PricesKeys::PRICE_SIMPLE_KEY]->value ?? 0;
	}

	private function setPriceSilver($fields)
	{
		$this->priceSilver = $fields[PricesKeys::PRICE_SILVER_KEY]->value ?? 0;
	}

	private function setPriceGold($fields)
	{
		$this->priceGold = $fields[PricesKeys::PRICE_GOLD_KEY]->value ?? 0;
	}

	private function setPriceSpecial($fields)
	{
		$this->priceSpecial = $fields[PricesKeys::PRICE_SPECIAL_KEY]->value ?? 0;
	}

	private function setPriceDelivery($fields)
	{
		$this->priceDelivery = $fields[PricesKeys::PRICE_DELIVERY_KEY]->value ?? 0;
	}

	private function setPriceFirstVisit($fields)
	{
		$this->priceFirstVisit = $fields[PricesKeys::PRICE_FIRST_VISIT_KEY]->value ?? 0;
	}

	private function setIsSpecial($fields)
	{
		$this->isSpecial = ($fields[PricesKeys::IS_SPECIAL_KEY]->value == 1) ? true : false;
	}

	private function setIsOriginal($fields)
	{
		$this->isOriginal = ($fields[PricesKeys::IS_ORIGINAL_KEY]->value == 1) ? true : false;
	}

	private function setIsByOrder($fields)
	{
		$this->isByOrder = ($fields[PricesKeys::IS_BY_ORDER_KEY]->value == 1) ? true : false;
	}

	public function renderPrice($price)
	{
		echo number_format($price, 0, '.', ' ') . $this->Rub;
	}

}