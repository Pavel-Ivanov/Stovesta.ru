<?php
defined('_JEXEC') or die();

JImport('b0.Item.Items');
JImport('b0.Work.WorkKeys');
JImport('b0.Company.CompanyConfig');
JImport('b0.Service.ServiceConfig');
JImport('b0.pricehelper');
//use Joomla\CMS\User\User;

class Works extends Items
{
	public function __construct($items, $paramsList = null)
	{
		parent::__construct($items, $paramsList);

//        $discountPriceFirstVisit = (1 - JComponentHelper::getParams('com_cobalt')->get('priceDiscountFirstVisit', 0) / 100);
		
		foreach ($items as $item) {
            $fields = $item->fields_by_key;
			$work = new stdClass();
			$work->id = $item->id;
			$work->url = $item->url;
			$work->title = $item->title;
			$work->controls = $item->controls;
			$work->image = $this->setImage($item);
			
			//Properties
			$work->models = $item->fields_by_key[WorkKeys::KEY_MODEL]->result ?? '';
			$work->motors = $item->fields_by_key[WorkKeys::KEY_MOTOR]->result ?? '';
			$work->serviceCode = $item->fields_by_key[WorkKeys::KEY_SERVICE_CODE]->result ?? '';
			$work->estimatedTime = $item->fields_by_key[WorkKeys::KEY_EXECUTION_TIME]->result ?? '';
			$work->subtitle = $item->fields_by_key[WorkKeys::KEY_SUBTITLE]->value ?? '';
			
			//Prices
			$work->isSpecial = isset($fields[WorkKeys::KEY_IS_SPECIAL]) ? $fields[WorkKeys::KEY_IS_SPECIAL]->value == 1 : false;
			$work->isGeneral = !$work->isSpecial;
			$work->priceGeneral = $item->fields_by_key[WorkKeys::KEY_PRICE_GENERAL]->value ?? 0;
			$work->priceSpecial = $item->fields_by_key[WorkKeys::KEY_PRICE_SPECIAL]->value ?? 0;
			$work->priceFirstVisit = isset($fields[WorkKeys::KEY_PRICE_GENERAL]) ?
                round($item->fields_by_key[WorkKeys::KEY_PRICE_GENERAL]->raw * ServiceConfig::DISCOUNT_PRICE_FIRST_VISIT) : 0;

			$work->yaCounter = CompanyConfig::COMPANY_YA_COUNTER;
			$work->yaCounterGoal = CompanyConfig::COMPANY_YA_COUNTER_GOAL_WORK;
			
			$this->items[$item->id] = $work;
		}
	}
	
	private function setImage($item)
	{
		return $item->fields_by_key[WorkKeys::KEY_IMAGE]->result ?? '<img src="'.$this->paramsList->get('tmpl_core.default_picture').
			'" alt="' . $item->title . '" title="' . $item->title . '">';
	}

	private function getFieldViewRights($field):string
	{
		return $field->params->get('core.field_view_access');
	}
	
	public function renderSubTitle(object $item)
	{
		if ($item->subtitle === '') {
			return;
		}
		echo '<p>'.$item->subtitle.'</p>';
	}
	
	public function renderField(string $tag, string $label, string $value)
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
	
	public function renderPrice(object $item)
	{
		if ($item->priceGeneral == 0) {
			return;
		}
        if ($item->isSpecial){
			echo '<p class="b0-price b0-price-special uk-text-danger">Специальная цена: ' . render_price($item->priceSpecial) . '</p>';
			echo '<p class="b0-price b0-price-general">Обычная цена: <del>' . render_price($item->priceGeneral) . '</del></p>';
			echo '<p>Вы экономите ' . render_economy($item->priceGeneral, $item->priceSpecial).'</p>';
		}
		else{
			echo '<p class="b0-price b0-price-general">Цена: ' . render_price($item->priceGeneral) . '</p>';
			echo '<p class="b0-price b0-price-delivery uk-text-danger">Цена первом визите: ' . render_price($item->priceFirstVisit) . '</p>';
			echo '<p>Вы экономите ' . render_economy($item->priceGeneral, $item->priceFirstVisit).'</p>';
		}
	}
	
	public function renderPriceRelated(object $item, string $linkDiscounts = '')
	{
		if ($item->priceGeneral == 0) {
			return;
		}
		if ($item->isSpecial) {
			echo '<p class="b0-price b0-price-related uk-text-danger">Специальная цена: ' . render_price($item->priceSpecial) . '</p>';
			echo '<p class="b0-price b0-price-related">Цена: <del>' . render_price($item->priceGeneral) . '</del>';
		}
		else {
			echo '<p class="b0-price b0-price-related">Цена: ' . render_price($item->priceGeneral). '</p>';
			echo '<p class="b0-price b0-price-related uk-text-danger">Цена при первом визите: ' . render_price($item->priceFirstVisit) . '</p>';
			echo '<p>Вы экономите ' . render_economy($item->priceGeneral, $item->priceFirstVisit).
				'&nbsp;<small>(Скидка по <a href="'. $linkDiscounts .
				'" target="_blank" title="акция Приятное знакомство">акции Приятное знакомство</a>)</small></p>';
		}
	}
	
}
