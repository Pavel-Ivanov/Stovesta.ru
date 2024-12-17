<?php
defined('_JEXEC') or die();
JImport('b0.Item.Item');
JImport('b0.Maintenance.MaintenanceKeys');
JImport('b0.Core.Prices');
JImport('b0.Core.PricesKeys');
JImport('b0.Core.Applicability');
JImport('b0.Core.ApplicabilityKeys');
JImport('b0.Core.Represent');
JImport('b0.Core.RepresentKeys');
JImport('b0.Core.Meta');
JImport('b0.Core.OpenGraph');
JImport('b0.Work.WorkKeys');
JImport('b0.Work.WorkIds');
JImport('b0.Sparepart.SparepartKeys');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.fixtures');

class Maintenance extends Item implements PricesKeys, ApplicabilityKeys, RepresentKeys
{
	use Prices, Applicability, Represent, OpenGraph, Meta;
	// Fields
	public $serviceCode;
	public $subTitle;
	public $searchSynonyms;
	// Состав ТО
	public $worksList = [];
	public $sparepartsList = [];
	public $sparepartsNum = [];
	public $estimatedTime;
	// Применяемость
	public $mileage;
	public $frequency;
	// Calculated parameters
	public $worksSum = 0;
	public $sparepartsSum = 0;
	public $totalSum = 0;
	public $workNumber; // номер техобслуживания
	
	public function __construct($item)
	{
		parent::__construct($item);
		$fields = $item->fields_by_key;
		// Fields
		$this->serviceCode = $fields[MaintenanceKeys::KEY_SERVICE_CODE]->result ?? '';
		$this->subTitle = $fields[MaintenanceKeys::KEY_SUBTITLE]->result ?? '';
		$this->searchSynonyms = $fields[MaintenanceKeys::KEY_SEARCH_SYNONYMS]->result ?? '';
		// Состав ТО
		$this->worksList = $this->getWorksList($fields);
		$this->sparepartsNum = $this->getSparepartsNum($fields);
		$this->sparepartsList = $this->getSparepartsList($fields);
		$this->estimatedTime = $fields[MaintenanceKeys::KEY_EXECUTION_TIME]->result ?? '';
		// Цены
		$this->setIsSpecial($fields);
		$this->setPriceSpecial($fields);
		// Применяемость
		$this->mileage = (int) ($fields[MaintenanceKeys::KEY_MILEAGE]->result ?? 0);
		$this->frequency = (int) ($fields[MaintenanceKeys::KEY_FREQUENCY]->result ?? 1);
		
		// Calculated parameters
		$this->worksSum = $this->getWorksSum();
		$this->sparepartsSum = $this->getSparepartsSum();
		$this->totalSum = $this->getTotalSum();
		$this->workNumber = $this->getWorkNumber();
		
		$this->setRepresent($fields);
		$this->setApplicability($fields);

		$this->title = $this->getTitle();
		$this->metaTitle = $this->getMetaTitle();
		$this->metaDescription = $this->getMetaDescription();
		$this->metaKey = '';
		
		$this->setOpenGraph($this);
	}
	
	private function getTitle(): string
	{
		$title = 'Техобслуживание ' . $this->models. ' ' . $this->mileage . ' км';
		if ($this->workNumber > 0) {
			$title .= ' (ТО-' . $this->workNumber . ')';
		}
		$title .= ', ' . $this->motors;
		$title .= ', ' . $this->years;
		return $title;
	}
	
	private function getMetaTitle(): string
	{
		$metaTitle = 'Регламент ТО ' . $this->models. ' ' . $this->mileage . ' км';
		if ($this->workNumber > 0) {
			$metaTitle .= ' (ТО-' . $this->workNumber . ')';
		}
		$metaTitle .= ', ' . $this->motors;
		$metaTitle .= ', ' . $this->years;
		$metaTitle .= ' в СтоВеста';
		return $metaTitle;
	}
	
	private function getMetaDescription(): string
	{
		$descr =  'Техническое обслуживание ' . $this->models . ', пробег ' . $this->mileage . ' км';
		if ($this->workNumber > 0) {
			$descr .= ' (ТО-' . $this->workNumber . '),';
		}
		$descr .= ' с мотором ' . $this->motors;
		$descr .= ', года выпуска ' . $this->years;
		return $descr;
	}
	
	private function getWorkNumber():int
	{
		return $this->mileage / $this->frequency;
	}
	
	private function getWorksList(array $fields): array
	{
		$arr = [];
		if (!isset($fields[MaintenanceKeys::KEY_WORKS_LIST])) {
			return $arr;
		}
		foreach ($fields[MaintenanceKeys::KEY_WORKS_LIST]->content['list'] as $work){
			$arr[$work->id] = [
				'title' => $work->title,
				'url' => JRoute::_($work->url),
				'price' => ($work->fields[WorkIds::ID_IS_SPECIAL] == 1) ?
					$work->fields[WorkIds::ID_PRICE_SPECIAL] :
					$work->fields[WorkIds::ID_PRICE_GENERAL],
				'isSpecial' => $work->fields[WorkIds::ID_IS_SPECIAL],
			];
		}
		return $arr;
	}
	
	private function getWorksSum(): int
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
	
	private function getSparepartsNum(array $fields): array
	{
		if (!isset($fields[MaintenanceKeys::KEY_SPAREPARTS_NUMBERS])) {
			return [];
		}
		$arr_num = str_getcsv($fields[MaintenanceKeys::KEY_SPAREPARTS_NUMBERS]->value, ",");
		$arr_numbers = [];
		foreach ($arr_num as $elem) {
			$pos_divider = strpos($elem ?? '', ":");
			$elem_id = mb_substr($elem ?? '', 0, $pos_divider);
			$elem_num = mb_substr($elem ?? '', $pos_divider + 1);
			$arr_numbers[$elem_id] = (int) $elem_num;
		}
		return $arr_numbers;
	}
	
	private function getSparepartsList(array $fields): array
	{
		$arr = [];
		if (!isset($fields[MaintenanceKeys::KEY_SPAREPARTS_LIST])) {
			return $arr;
		}
		foreach ($fields[MaintenanceKeys::KEY_SPAREPARTS_LIST]->content['list'] as $sparepart)
		{
			$arr[$sparepart->id] = [
				'title' => $sparepart->title,
				'url' => JRoute::_($sparepart->url),
				'price' => ($sparepart->fields[SparepartIds::ID_IS_SPECIAL] == 1) ? $sparepart->fields[SparepartIds::ID_PRICE_SPECIAL] : $sparepart->fields[SparepartIds::ID_PRICE_GENERAL],
				'isSpecial' => $sparepart->fields[SparepartIds::ID_IS_SPECIAL],
				'num' => array_key_exists($sparepart->id, $this->sparepartsNum) ? $this->sparepartsNum[$sparepart->id] : 1,
			];
		}
		$this->getSparepartsNum($fields);
		return $arr;
	}
	
	private function getSparepartsSum(): int
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
	
	private function getTotalSum(): int
	{
		return $this->totalSum = $this->sparepartsSum + $this->worksSum;
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
		            <td class="uk-text-right">Итого стоимость работ: </td>
		            <td>';
		                echo $this->worksSum . $this->iconRub;
		            echo '</td>
		        </tr>
		    </tbody>
		</table>';
	}
	
	public function renderSparepartsList()
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
                foreach ($this->sparepartsList as $id => $sparepart) {
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
		            <td colspan="3" class="uk-text-right">Итого стоимость запчастей: </td>
		            <td class="uk-text-right">';
		                echo $this->sparepartsSum . $this->iconRub;
		            echo '</td>
		        </tr>
		    </tbody>
		</table>';
	}
	
	public function renderTotalSum()
	{
		echo 'Всего: ' . $this->totalSum . $this->iconRub;
	}
	
	public function renderExecutionTime()
	{
		if (!$this->estimatedTime) {
			return;
		}
		echo '<p class="lrs-price-second">';
		echo 'Ориентировочное время выполнения работы: ' . $this->estimatedTime;
		echo '</p>';
	}
}
