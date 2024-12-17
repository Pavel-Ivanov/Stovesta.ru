<?php
defined('_JEXEC') or die;
JImport('b0.Item.Item');
JImport('b0.Core.Prices');
JImport('b0.Core.PricesKeys');
JImport('b0.Core.Represent');
JImport('b0.Core.RepresentKeys');
JImport('b0.Core.Applicability');
JImport('b0.Core.ApplicabilityKeys');
JImport('b0.Core.OpenGraph');
JImport('b0.Core.Meta');
JImport('b0.Work.WorkIds');
JImport('b0.Work.WorkKeys');

class Work extends Item implements PricesKeys, RepresentKeys, ApplicabilityKeys
{
	use Prices, Represent, Applicability, OpenGraph, Meta;
	//***
	private const TITLE_SPAREPARTS = 'Рекомендуемые запчасти';
	private const TITLE_ACCESSORIES = 'Рекомендуемые аксессуары';
//	private const TITLE_WORKS = 'Сопутствующие работы';
	private const TITLE_ARTICLES = 'Статьи';
	// Fields
	public $serviceCode;
	public $subTitle;
	public $estimatedTime;
	// Tabs
	private $tabsTemplate = [
		WorkKeys::KEY_SPAREPARTS => [
			'title' => Work::TITLE_SPAREPARTS,
			'isActive' => 1,
		],
		WorkKeys::KEY_ACCESSORIES => [
			'title' => Work::TITLE_ACCESSORIES,
			'isActive' => 0,
		],
/*		WorkKeys::KEY_WORKS => [
			'title' => Work::TITLE_WORKS,
			'isActive' => 0,
		],*/
		WorkKeys::KEY_ARTICLES => [
			'title' => Work::TITLE_ARTICLES,
			'isActive' => 0,
		],
	];
	public $tabs = [];

	public function __construct($item, $user = null, $microdata = null)
	{
		parent::__construct($item, $user);
		$fields = $item->fields_by_key;
		$this->serviceCode = $fields[WorkKeys::KEY_SERVICE_CODE]->result ?? '';
		$this->subTitle = $fields[WorkKeys::KEY_SUBTITLE]->result ?? '';
		$this->estimatedTime = $fields[WorkKeys::KEY_EXECUTION_TIME]->result ?? '';
		// Set Prices
		$this->setPriceGeneral($fields);
		$this->setPriceSimple($fields);
		$this->setPriceSilver($fields);
		$this->setPriceGold($fields);
		$this->setPriceSpecial($fields);
		$this->setPriceFirstVisit($fields);
		$this->setIsSpecial($fields);
		// Set Represent
		$this->setRepresent($fields);
		// Set Applicability
		$this->setApplicability($fields);

		$this->metaTitle = $this->setMetaTitle();
		$this->metaDescription = $this->setMetaDescription($item);
		$this->metaKey = '';
		// Set Open Graph
		$this->setOpenGraph($this);
		// Set Tabs
		foreach ($this->tabsTemplate as $key => $tab) {
			if (!isset($fields[$key])) {
				continue;
			}
			if ($fields[$key]->content['total'] === 0) {
				continue;
			}
			$this->tabs[$key] = [
				'title' => $tab['title'],
				'isActive' => $tab['isActive'],
				'total' => $fields[$key]->content['total'],
				'result' => $fields[$key]->content['html']
			];
		}
	}

	private function setMetaTitle(): string
	{
		return $this->title . ' за ' . $this->priceGeneral . ' рублей в '. $this->siteName;
	}

	private function setMetaDescription($item): string
	{
		return $this->title . ' за ' . $this->priceGeneral . ' рублей в '. $this->siteName;
	}

	public function renderSubTitle()
	{
		if (!$this->subTitle) {
			return;
		}
		echo '<p class="uk-article-lead">' . $this->subTitle . '</p>';
	}

	public function renderEstimatedTime()
	{
		if (!$this->estimatedTime) {
			return;
		}
		echo '<p>';
		echo '<strong>Ориентировочное время выполнения работы: </strong>'.$this->estimatedTime;
		echo '</p>';
	}

	public function renderServiceCode()
	{
		if (!$this->serviceCode) {
			return;
		}
		echo '<p><strong>Код услуги: </strong>'.$this->serviceCode.'</p>';
	}

	public function renderEconomy($firstPrice, $secondPrice)
	{
		if (!$this->serviceCode) {
			return;
		}
		echo '<p><strong>Код услуги: </strong>'.$this->serviceCode.'</p>';
	}
	
	function getEconomy($price1, $price2)
	{
		$delta = $price1 - $price2;
		$percent = number_format(($delta / $price1) * 100, 0);
		return number_format($delta, 0, '.', ' ') . $this->iconRub;
	}
	
	public function renderModels()
	{
		if (!$this->models) {
			return;
		}
		echo '<p><strong>Модели: </strong>'.$this->models.'</p>';
	}
	
	public function renderMotors()
	{
		if (!$this->motors) {
			return;
		}
		echo '<p><strong>Моторы: </strong>'.$this->motors.'</p>';
	}
}
