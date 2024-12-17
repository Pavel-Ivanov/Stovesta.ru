<?php
defined('_JEXEC') or die();
JImport('b0.Item.Item');
JImport('b0.ChipTuning.ChipTuningKeys');
JImport('b0.Core.Prices');
JImport('b0.Core.PricesKeys');
JImport('b0.Core.Represent');
JImport('b0.Core.RepresentKeys');
JImport('b0.Core.Applicability');
JImport('b0.Core.ApplicabilityKeys');
JImport('b0.Core.OpenGraph');

class ChipTuning extends Item implements PricesKeys, RepresentKeys, ApplicabilityKeys
{
	use Prices, Represent, Applicability, OpenGraph;
	// Meta
	public string $metaTitle;
	public string $metaDescription;

	// Fields
	public string $productCode;
	public string $subTitle;
	public string $shortDescription;
	public ?array $teaserVideo;
	public ?array $reviews;
	public string $guarantee;

	public function __construct($item, $user = null, $microdata = null)
	{
		parent::__construct($item, $user);
		$fields = $item->fields_by_key;
		$this->productCode = $fields[ChipTuningKeys::KEY_PRODUCT_CODE]->result ?? '';
		$this->subTitle = $fields[ChipTuningKeys::KEY_SUBTITLE]->result ?? '';
		$this->shortDescription = $fields[ChipTuningKeys::KEY_SHORT_DESCRIPTION]->result ?? '';
		//$this->teaserVideo = $fields[ChipTuning::KEY_TEASER_VIDEO]->result ?? null;
		if (!isset($fields[ChipTuningKeys::KEY_TEASER_VIDEO])){
			$this->teaserVideo = null;
		}
		else {
			$this->teaserVideo = [
				'result' => $fields[ChipTuningKeys::KEY_TEASER_VIDEO]->result,
				'url' => $fields[ChipTuningKeys::KEY_TEASER_VIDEO]->value['link']
			];
		}

		if (!isset($fields[ChipTuningKeys::KEY_REVIEWS])){
			$this->reviews = null;
		}
		else {
			$this->reviews = [
				'result' => $fields[ChipTuningKeys::KEY_REVIEWS]->result,
			];
			$this->reviews['url'] = [];
			foreach ($fields[ChipTuningKeys::KEY_REVIEWS]->value as $link) {
				$this->reviews['url'][] = $link['fullpath'];
			}
		}

		$this->guarantee = $fields[ChipTuningKeys::KEY_GUARANTEE]->result ?? '';

		// Prices trait
		$this->setPriceGeneral($fields);
//		$this->setIsSpecial($fields);
//		$this->setPriceSpecial($fields);
		// Represent trait
		$this->setRepresent($fields);
		// Applicability trait
		$this->setApplicability($fields);

		$this->title = $this->setTitle();
		$this->metaTitle = $this->setMetaTitle();
		$this->metaDescription = $this->setMetaDescription();
		$this->metaKey = '';

		$this->setOpenGraph($this);
	}

	private function setTitle(): string
	{
		return $this->title;
	}

	private function setMetaTitle(): string
	{
		return $this->title . ' в StoVesta';
	}

	private function setMetaDescription(): string
	{
		return $this->title . ' в StoVesta';
	}

	public function renderShortDescription(): void
	{
		if (!$this->shortDescription) {
			return;
		}
		echo $this->shortDescription;
	}

	public function renderTeaserVideo(): void
	{
		if (!$this->teaserVideo) {
			return;
		}
		echo '<div class="uk-margin-top uk-margin-large-bottom">';
		echo '<hr class="uk-article-divider">';
		echo $this->teaserVideo['result'];
		echo '</div>';
	}

	public function renderReviews(): void
	{
		if (!$this->reviews) {
			return;
		}
		echo '<div class="uk-margin-top uk-margin-large-bottom">';
		echo '<hr class="uk-article-divider">';
		echo $this->reviews['result'];
		echo '</div>';
	}

	public function renderGuarantee(): void
	{
		if (!$this->guarantee) {
			return;
		}
		echo $this->guarantee;
	}

}