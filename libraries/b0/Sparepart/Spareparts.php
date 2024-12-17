<?php
defined('_JEXEC') or die();

JImport('b0.Sparepart.SparepartKeys');
JImport('b0.Product.ProductConfig');
JImport('b0.Company.CompanyConfig');
JImport('b0.pricehelper');
//use Joomla\CMS\User\User;

class Spareparts
{
	public array $items;
	
	public function __construct($items, $paramsList)
	{
		$cart = JFactory::getApplication()->getUserState('cart') ?? [];
		$user = JFactory::getUser();
		foreach ($items as $item) {
			$sparepart = new stdClass();
			$sparepart->id = $item->id;
			$sparepart->url = $item->url;
			$sparepart->title = $item->title;
			$sparepart->controls = $item->controls;
			$sparepart->image = $this->setImage($item, $paramsList->get('tmpl_core.default_picture', '/images/elements/no-photo.jpg'));
			
			//Properties
			$sparepart->models = $item->fields_by_key[SparepartKeys::KEY_MODEL]->result ?? '';
			$sparepart->motors = $item->fields_by_key[SparepartKeys::KEY_MOTOR]->result ?? '';
			$sparepart->manufacturer = $item->fields_by_key[SparepartKeys::KEY_MANUFACTURER]->result ?? '';
			$sparepart->productCode = $item->fields_by_key[SparepartKeys::KEY_PRODUCT_CODE]->result ?? '';
			$sparepart->subtitle = $item->fields_by_key[SparepartKeys::KEY_SUBTITLE]->value ?? '';
			
			//Prices
			$sparepart->isSpecial = $item->fields_by_key[SparepartKeys::KEY_IS_SPECIAL]->value == 1;
			$sparepart->isByOrder = $item->fields_by_key[SparepartKeys::KEY_IS_BY_ORDER]->value == 1;
			$sparepart->isOriginal = $item->fields_by_key[SparepartKeys::KEY_IS_ORIGINAL]->value == 1;
			$sparepart->isGeneral = !$sparepart->isSpecial && !$sparepart->isByOrder;
			$sparepart->priceGeneral = $item->fields_by_key[SparepartKeys::KEY_PRICE_GENERAL]->value ?? 0;
			$sparepart->priceSpecial = $item->fields_by_key[SparepartKeys::KEY_PRICE_SPECIAL]->value ?? 0;
//			$sparepart->priceGold = $item->fields_by_key[SparepartKeys::KEY_PRICE_GOLD]->value ?? 0;
			$sparepart->priceGold = $this->setPriceGold($sparepart->priceGeneral, $sparepart->isOriginal, ProductConfig::PRICE_DISCOUNT_ORIGINAL_GOLD, ProductConfig::PRICE_DISCOUNT_NON_ORIGINAL_GOLD, 'Золотой уровень');

			if (isset($item->fields_by_key[SparepartKeys::KEY_VENDOR_CODE]) && in_array($this->getFieldViewRights($item->fields_by_key[SparepartKeys::KEY_VENDOR_CODE]), $user->getAuthorisedViewLevels(), true)) {
				$sparepart->vendorCode = $item->fields_by_key[SparepartKeys::KEY_VENDOR_CODE]->value;
			}
			else {
				$sparepart->vendorCode = '';
			}
			
			$sparepart->isInCart = $this->isInCart($item->id, $cart);
			$sparepart->cartQuantity = $this->isInCart($item->id, $cart) ? (int) $cart[$item->id]['quantity'] : 0;
			
			$sparepart->yaCounter = CompanyConfig::COMPANY_YA_COUNTER;
			$sparepart->yaCounterGoal = CompanyConfig::COMPANY_YA_COUNTER_GOAL_SPAREPART;
			
			$this->items[$item->id] = $sparepart;
		}
	}
	
	private function setImage($item, $defaultPicture): string
	{
		return $item->fields_by_key[SparepartKeys::KEY_IMAGE]->result ??
		'<a href="'. JRoute::_($item->url) . '" target="_blank"><img src="' . $defaultPicture .
		'" alt="' . $item->title . '" title="' . $item->title . '"></a>';
	}

    private function setPriceGold($priceGeneral, $isOriginal, $discountOriginal, $discountNonOriginal, $label = ''): int
    {
        return round($isOriginal ? $priceGeneral * $discountOriginal : $priceGeneral * $discountNonOriginal);
    }

    private function getFieldViewRights($field):string
	{
		return $field->params->get('core.field_view_access');
	}
	
	private function isInCart($id, $cart):bool
	{
		return array_key_exists($id, $cart);
	}
	
	public function renderSubTitle(object $item): void
	{
		if ($item->subtitle === '') {
			return;
		}
		echo '<p>'.$item->subtitle.'</p>';
	}
	
	public function renderField(string $tag, string $label, string $value): void
	{
		if ($value === '') {
			return;
		}
		$str = '<'.$tag.'>';
		if ($label !== '') {
			$str .= '<strong>' . $label .': </strong>';
		}
		$str .= $value;
		$str .= '</'.$tag.'>';
		echo $str;
	}
	
	public function renderPrice(object $item): void
	{
		if ($item->priceGeneral == 0) {
			return;
		}
/*		if ($item->isByOrder){
			echo '<p class="b0-price b0-price-general">Ожидается поступление</p>';
			echo '<p class="b0-price b0-price-general uk-text-muted">Цена уточняется при заказе</p>';
		}*/
        if ($item->isSpecial){
			echo '<p class="b0-price b0-price-special uk-text-danger">Специальная цена: ' . render_price($item->priceSpecial) . '</p>';
			echo '<p class="b0-price b0-price-general">Обычная цена: <del>' . render_price($item->priceGeneral) . '</del></p>';
			echo '<p>Вы экономите ' . render_economy($item->priceGeneral, $item->priceSpecial).'</p>';
		}
		else{
			echo '<p class="b0-price b0-price-general">Цена: ' . render_price($item->priceGeneral) . '</p>';
			echo '<p class="b0-price b0-price-delivery uk-text-danger">Цена при доставке по СПб: ' . render_price($item->priceDelivery) . '</p>';
			echo '<p>Вы экономите ' . render_economy($item->priceGeneral, $item->priceDelivery).'</p>';
		}
	}
	
	public function renderPriceRelated(object $item, string $linkDelivery = ''): void
	{
		if ($item->priceGeneral == 0) {
			return;
		}
		if ($item->isSpecial) {
			echo '<p class="b0-price b0-price-related uk-text-danger">Специальная цена: ' . render_price($item->priceSpecial) . '</p>';
			echo '<p class="b0-price b0-price-related">Цена: <del>' . render_price($item->priceGeneral) . '</del>';
		}
		elseif ($item->isOriginal) {
			echo '<p class="b0-price b0-price-related uk-text-danger">Цена: ' . render_price($item->priceGeneral) . '</p>';
		}
		else {
			echo '<p class="b0-price b0-price-related uk-text-danger">Цена по золотой карте: ' . render_price($item->priceGold) . '</p>';
			echo '<p class="b0-price b0-price-related">Цена без карты: ' . render_price($item->priceGeneral). '</p>';
		}
	}
	
	public function renderCart(object $item): void
	{
		//TODO
	}
}
