<?php
defined('_JEXEC') or die;
JImport('b0.Item.Item');
JImport('b0.Bundle.BundleKeys');
JImport('b0.Core.Prices');
JImport('b0.Core.PricesKeys');
JImport('b0.Core.Represent');
JImport('b0.Core.RepresentKeys');
JImport('b0.Core.Applicability');
JImport('b0.Core.ApplicabilityKeys');
JImport('b0.Core.Meta');
JImport('b0.Core.OpenGraph');
JImport('b0.Sparepart.SparepartKeys');
JImport('b0.Accessory.AccessoryKeys');
JImport('b0.Work.WorkKeys');

class Bundle extends Item implements PricesKeys, RepresentKeys, ApplicabilityKeys
{
	use Prices, Represent, Applicability, Meta, OpenGraph;
	//***
	public const TOTAL_DISCOUNT_PERCENT = 15;
	// Fields
	public $productCode;
	public $subTitle;
	public $teaserDescription;
	public $searchSynonyms;

	public $sparepartsList = [];
	public $sparepartsNumbers = [];
	public $sparepartsSum = 0;
	public $accessoriesList = [];
	public $accessoriesNumbers = [];
	public $accessoriesSum = 0;
	public $goodsList = [];
	public $goodsSum = 0;
	public $worksList = [];
	public $worksSum = 0;
	public $totalSum = 0;
	public $discountSum = 0;
	public $economySum = 0;
	public $executionTime = '';

	public function __construct($item, $user = null, $microdata = null)
	{
		parent::__construct($item, $user);
		//*** Поля
		$fields = $item->fields_by_key;
		$this->productCode = $fields[BundleKeys::KEY_PRODUCT_CODE]->result ?? '';
		$this->subTitle = $fields[BundleKeys::KEY_SUBTITLE]->result ?? '';
		$this->teaserDescription = $fields[BundleKeys::KEY_TEASER_DESCRIPTION]->result ?? '';
		$this->searchSynonyms = $fields[BundleKeys::KEY_SEARCH_SYNONYMS]->result ?? '';
		//*** Состав
		$this->sparepartsNum = $this->setSparepartsNumbers($fields);
		$this->sparepartsList = $this->setSparepartsList($fields);
		$this->sparepartsSum = $this->setSparepartsSum();
		$this->accessoriesNumbers = $this->setAccessoriesNumbers($fields);
		$this->accessoriesList = $this->setAccessoriesList($fields);
		$this->accessoriesSum = $this->setAccessoriesSum();
		$this->goodsList = $this->setGoodsList();
		$this->goodsSum = $this->setGoodsSum();
		$this->worksList = $this->setWorksList($fields);
		$this->worksSum = $this->setWorksSum();
		$this->totalSum = $this->setTotalSum();
		$this->discountSum = $this->setDiscountSum();
		$this->economySum = $this->setEconomySum();
		$this->executionTime = $fields[BundleKeys::KEY_EXECUTION_TIME]->result ?? '';
		//*** Цены
		$this->setPriceGeneral($fields);
		$this->setIsSpecial($fields);
		$this->setPriceSpecial($fields);
		//*** Описание
		$this->setRepresent($fields);
		//*** Применяемость
		$this->setApplicability($fields);
		//*** OpenGraph
		$this->setOpenGraph($this);
		//*** Мета
		$this->metaTitle = $this->setMetaTitle();
		$this->metaDescription = $this->setMetaDescription($item);
		$this->metaKey = $this->setMetaKeys($item);;

		$this->title = $this->setTitle();
	}

	private function setTitle(): string
	{
		return $this->title;
	}

	private function setMetaTitle(): string
	{
		return $this->title . ' за ' . $this->priceGeneral . ' рублей в ' . $this->siteName;
	}

	private function setMetaDescription($item): string
	{
		return $this->title . ' за ' . $this->priceGeneral . ' рублей в ' . $this->siteName;
	}

	private function setMetaKeys($item): string
	{
		return $item->meta_key;
	}

	private function setSparepartsNumbers($fields): array
	{
		if (!isset($fields[BundleKeys::KEY_SPAREPARTS_NUMBERS])) {
			return [];
		}
		$arr_num = str_getcsv($fields[BundleKeys::KEY_SPAREPARTS_NUMBERS]->result, ",");
		$arr_numbers = [];
		foreach ($arr_num as $elem) {
			$pos_divider = strpos($elem, ":");
			$elem_id = mb_substr($elem, 0, $pos_divider);
			$elem_num = mb_substr($elem, $pos_divider + 1);
			$arr_numbers[$elem_id] = (int) $elem_num;
		}
		return $arr_numbers;
	}

	private function setSparepartsList($fields): array
	{
		if (!isset($fields[BundleKeys::KEY_SPAREPARTS_LIST]->content['list'])) {
			return [];
		}
		$arr = [];
		foreach ($fields[BundleKeys::KEY_SPAREPARTS_LIST]->content['list'] as $sparepart)
		{
			$fieldsByKey = $sparepart->fields_by_key;
			$arr[$sparepart->id] = [
				'title' => $sparepart->title,
				'url' => JRoute::_($sparepart->url),
				'price' => ($fieldsByKey[SparepartKeys::KEY_IS_SPECIAL]->raw == 1) ?
					$fieldsByKey[SparepartKeys::KEY_PRICE_SPECIAL]->raw : $fieldsByKey[SparepartKeys::KEY_PRICE_GENERAL]->raw,
				'isSpecial' => $sparepart->fields_by_key[SparepartKeys::KEY_IS_SPECIAL]->raw,
				'num' => array_key_exists($sparepart->id, $this->sparepartsNumbers) ? $this->sparepartsNumbers[$sparepart->id] : 1,
			];
		}
		return $arr;
	}

	private function setSparepartsSum(): int
	{
		if (!$this->sparepartsList) {
			return 0;
		}
		$sum = 0;
		foreach ($this->sparepartsList as $sparepart) {
			$sum += $sparepart['price'] * $sparepart['num'];
		}
		return $sum;
	}

	private function setAccessoriesNumbers($fields): array
	{
		if (!isset($fields[BundleKeys::KEY_ACCESSORIES_NUMBERS])) {
			return [];
		}
		$arr_num = str_getcsv($fields[BundleKeys::KEY_ACCESSORIES_NUMBERS]->result, ",");
		$arr_numbers = [];
		foreach ($arr_num as $elem) {
			$pos_divider = strpos($elem, ":");
			$elem_id = mb_substr($elem, 0, $pos_divider);
			$elem_num = mb_substr($elem, $pos_divider + 1);
			$arr_numbers[$elem_id] = (int) $elem_num;
		}
		return $arr_numbers;
	}

	private function setAccessoriesList($fields): array
	{
		if (!isset($fields[BundleKeys::KEY_ACCESSORIES_LIST]->content['list'])) {
			return [];
		}
		$arr = [];
		foreach ($fields[BundleKeys::KEY_ACCESSORIES_LIST]->content['list'] as $accessory)
		{
			$fieldsByKey = $accessory->fields_by_key;
			$arr[$accessory->id] = [
				'title' => $accessory->title,
				'url' => JRoute::_($accessory->url),
				'price' => ($fieldsByKey[AccessoryKeys::KEY_IS_SPECIAL]->raw == 1) ?
					$fieldsByKey[AccessoryKeys::KEY_PRICE_SPECIAL]->raw : $fieldsByKey[AccessoryKeys::KEY_PRICE_GENERAL]->raw,
				'isSpecial' => $fieldsByKey[AccessoryKeys::KEY_IS_SPECIAL]->raw,
				'num' => array_key_exists($accessory->id, $this->accessoriesNumbers) ? $this->accessoriesNumbers[$accessory->id] : 1,
			];
		}
		return $arr;
	}

	private function setAccessoriesSum(): int
	{
		if (!$this->accessoriesList) {
			return 0;
		}
		$sum = 0;
		foreach ($this->accessoriesList as $accessory) {
			$sum += $accessory['price'] * $accessory['num'];
		}
		return $sum;
	}
	
	private function setGoodsList(): array
	{
		return $this->sparepartsList + $this->accessoriesList;
	}
	
	private function setGoodsSum(): int
	{
		return $this->sparepartsSum + $this->accessoriesSum;
	}

	private function setWorksList($fields): array
	{
		if (!isset($fields[BundleKeys::KEY_WORKS_LIST]->content['list'])) {
			return [];
		}
		$arr = [];
		foreach ($fields[BundleKeys::KEY_WORKS_LIST]->content['list'] as $work)
		{
			$fieldsByKey = $work->fields_by_key;
			$arr[$work->id] = [
				'title' => $work->title,
				'url' => JRoute::_($work->url),
				'price' => ($fieldsByKey[WorkKeys::KEY_IS_SPECIAL]->raw == 1) ?
					$fieldsByKey[WorkKeys::KEY_PRICE_SPECIAL]->raw : $fieldsByKey[WorkKeys::KEY_PRICE_GENERAL]->raw,
				'isSpecial' => $fieldsByKey[WorkKeys::KEY_IS_SPECIAL]->raw,
			];
		}
		return $arr;
	}

	private function setWorksSum(): int
	{
		if (!$this->worksList) {
			return 0;
		}
		$sum = 0;
		foreach ($this->worksList as $work) {
			$sum += $work['price'];
		}
		return $sum;
	}

	private function setTotalSum(): int
	{
		return $this->sparepartsSum + $this->accessoriesSum + $this->worksSum;
	}

	private function setDiscountSum(): int
	{
		return round($this->totalSum * (100 - bundle::TOTAL_DISCOUNT_PERCENT) / 100, 0);
	}

	private function setEconomySum(): int
	{
		return round($this->totalSum - $this->discountSum, 0);
	}

	public function renderSubTitle() {
		if (mb_strlen($this->subTitle) == 0) {
			return;
		}
		echo '<p class="uk-article-lead">' . $this->subTitle . '</p>';
	}

	public function renderTeaserDescription()
	{
		if (!$this->teaserDescription) {
			return;
		}
		echo $this->teaserDescription;
	}

	public function renderGoodsList()
	{
		echo '<table class="uk-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Наименование</th>';
		echo '<th class="uk-text-center">Количество</th>';
		echo '<th class="uk-text-center">Цена</th>';
		echo '<th>Стоимость</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ($this->goodsList as $id => $sparepart) {
			echo '<tr  class="lrs-article-title-related">';
			echo '<td>';
			echo '<a href="'. JRoute::_($sparepart['url']).'" target="_blank">';
			echo $sparepart['title'];
			if ($sparepart['isSpecial'] == 1) {
				echo ' / <span class="uk-text-warning">cпецпредложение</span>';
			}
			echo '</a>';
			echo '</td>';
			echo '<td class="uk-text-center">';
			echo $sparepart['num'];
			echo '</td>';
			echo '<td class="uk-text-center">';
			echo $sparepart['price'] . $this->iconRub;
			echo '</td>';
			echo '<td class="uk-text-right">';
			echo $sparepart['price'] * $sparepart['num'] . $this->iconRub;
			echo '</td>';
			echo '</tr>';
		}
		echo '<tr class="lrs-article-title-related">
		            <td colspan="3" class="uk-text-right">Стоимость запчастей: </td>
		            <td class="uk-text-right">';
		echo $this->goodsSum . $this->iconRub;
		echo '</td>
		        </tr>
		    </tbody>
		</table>';
	}

	public function renderWorksList()
	{
		echo '<table class="uk-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Наименование</th>';
		echo '<th>Цена</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ($this->worksList as $id => $work) {
			echo '<tr  class="lrs-article-title-related">';
			echo '<td>';
			echo '<a href="'. JRoute::_($work['url']).'" target="_blank">';
			echo $work['title'];
			if ($work['isSpecial'] == 1) {
				echo ' / <span class="uk-text-warning">cпецпредложение</span>';
			}
			echo '</a>';
			echo '</td>';
			echo '<td>';
			echo $work['price'] . $this->iconRub;
			echo '</td>';
			echo '</tr>';
		}
		echo '<tr  class="lrs-article-title-related">
		            <td class="uk-text-right">Стоимость работ: </td>
		            <td>';
		echo $this->worksSum . $this->iconRub;
		echo '</td>
		        </tr>
		    </tbody>
		</table>';
	}

	public function renderTotalSum()
	{
		echo 'Итого: ' . $this->totalSum . ' - ' . bundle::TOTAL_DISCOUNT_PERCENT . '% = <span class="uk-text-danger ls-price-first">' . $this->discountSum . '</span>' . $this->iconRub;
	}

	public function renderExecutionTime()
	{
		if (!$this->executionTime) {
			return;
		}
		echo '<p class="uk-text-rightlrs-price-second">';
		echo 'Ориентировочное время выполнения работы: ' . $this->executionTime;
		echo '</p>';
	}
}
