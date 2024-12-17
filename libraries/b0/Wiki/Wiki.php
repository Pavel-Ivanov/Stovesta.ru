<?php
defined('_JEXEC') or die;
JImport('b0.Item.Item');
JImport('b0.Wiki.WikiKeys');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Core.Represent');
JImport('b0.Core.RepresentKeys');
JImport('b0.Core.OpenGraph');
JImport('b0.Company.CompanyConfig');

class Wiki extends Item  implements RepresentKeys
{
	use Represent, OpenGraph;
	//***
	private const TITLE_RELATED_ARTICLES = 'Рекомендуем прочитать';
	private const TITLE_RELATED_GOODS = 'Об этом писалось в статье';
	private const TITLE_RELATED_WORKS = 'Сопутствующие работы';
	// Meta
	public string $metaTitle;
	public string $metaDescription;
	public string $metaKey;
	public $microdata;
	// Fields
	public string $announcement;
	public string $contents;
	public string $body;
	public string $author;
	public string $site;
	
	public array $goods = [];
	public string $publisher;
	// Tabs
	private array $tabsTemplate = [
		WikiKeys::KEY_RELATED_ARTICLES => [
			'title' => Wiki::TITLE_RELATED_ARTICLES,
			'isActive' => 1,
		],
		WikiKeys::KEY_RELATED_WORKS => [
			'title' => Wiki::TITLE_RELATED_WORKS,
			'isActive' => 0,
		],
	];
	public array $tabs = [];
	
	public function __construct($item, $user = null, $microdata = null)
	{
		parent::__construct($item, $user);
		$fields = $item->fields_by_key;
		$this->announcement = $fields[WikiKeys::KEY_ANNOUNCEMENT]->result ?? '';
		$this->contents = $fields[WikiKeys::KEY_CONTENTS]->result ?? '';
		$this->body = $fields[WikiKeys::KEY_BODY]->result ?? '';
		$this->author = $fields[WikiKeys::KEY_AUTHOR]->result ?? CompanyConfig::COMPANY_NAME;
		$this->site = $fields[WikiKeys::KEY_SITE]->result ?? '';
		
		$this->setGoods($fields);
		$this->publisher = JFactory::getUser($item->user_id)->name;
		
		$this->setImage($fields);
		$this->setGallery($fields);
		$this->setVideo($fields);
		
		$this->metaTitle = $this->setMetaTitle($item, $fields[WikiKeys::KEY_ANNOUNCEMENT]->raw);
		$this->metaDescription = $this->setMetaDescription($item);
		$this->metaKey = '';

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
	
	private function setMetaTitle(object $item, string $announcement): string
	{
		return $item->meta_key !== '' ? $item->meta_key : $announcement;
	}
	
	private function setMetaDescription(object $item): string
	{
		return $item->meta_descr;
	}
	
	private function setGoods($fields)
	{
        $this->goods = [];
		if (isset($fields[WikiKeys::KEY_RELATED_SPAREPARTS]->content['list'])) {
			foreach ($fields[WikiKeys::KEY_RELATED_SPAREPARTS]->content['list'] as $goodsItem) {
				$this->goods[$goodsItem->id] = [
					'title' => $goodsItem->title,
					'url' => JRoute::_($goodsItem->url),
					'imageUrl' => $this->setImageUrlSparepart($goodsItem->fields, RepresentKeys::NO_IMAGE_PATH),
					'priceGeneral' => $goodsItem->fields[SparepartIds::ID_PRICE_GENERAL],
					'priceSpecial' => $goodsItem->fields[SparepartIds::ID_PRICE_SPECIAL],
					'isSpecial' => $goodsItem->fields[SparepartIds::ID_IS_SPECIAL],
					'isByOrder' => $goodsItem->fields[SparepartIds::ID_IS_BY_ORDER],
				];
			}
		}
		if (isset($fields[WikiKeys::KEY_RELATED_ACCESSORIES]->content['list'])) {
			foreach ($fields[WikiKeys::KEY_RELATED_ACCESSORIES]->content['list'] as $goodsItem) {
				$this->goods[$goodsItem->id] = [
					'title' => $goodsItem->title,
					'url' => JRoute::_($goodsItem->url),
                    'imageUrl' => $this->setImageUrlAccessory($goodsItem->fields, RepresentKeys::NO_IMAGE_PATH),
                    'priceGeneral' => $goodsItem->fields[AccessoryIds::ID_PRICE_GENERAL],
					'priceSpecial' => $goodsItem->fields[AccessoryIds::ID_PRICE_SPECIAL],
					'isSpecial' => $goodsItem->fields[AccessoryIds::ID_IS_SPECIAL],
					'isByOrder' => $goodsItem->fields[AccessoryIds::ID_IS_BY_ORDER],
				];
			}
		}
	}

    private function setImageUrlSparepart($fields, $defaultPicture): string
    {
        return $fields[SparepartIds::ID_IMAGE]['image'] ?? $defaultPicture;
    }

    private function setImageUrlAccessory($fields, $defaultPicture): string
    {
        return $fields[AccessoryIds::ID_IMAGE]['image'] ?? $defaultPicture;
    }

    public function renderContents()
	{
		if (!$this->contents) {
			return;
		}
		echo $this->contents;
	}
	
	public function renderBody()
	{
		if (!$this->body) {
			return;
		}
		echo $this->body;
	}
	
	public function renderGoods()
	{
		if (!$this->goods) {
			return;
		}
		?>
        <ul class="uk-grid uk-grid-width-medium-1-2 uk-grid-match" data-uk-grid-margin data-uk-grid-match="{target:'.uk-panel'}">
		    <?php foreach ($this->goods as $goodsItem) :?>
                <li>
                    <div class="uk-panel uk-panel-box">
                        <div class="uk-grid">
                            <div class="uk-width-1-3">
                                <a href="<?= $goodsItem['url'] ?>" title="<?= $goodsItem['title'] ?>" target="_blank">
                                    <img src="<?= $goodsItem['imageUrl'] ?>" alt="<?= $goodsItem['title'] ?>">
                                </a>
                            </div>
                            <div class="uk-width-2-3">
                                <p class="b0-title-related">
                                    <a href="<?= $goodsItem['url'] ?>" title="<?= $goodsItem['title'] ?>" target="_blank">
									    <?= $goodsItem['title'] ?>
                                    </a>
                                </p>
                                <hr>
							    <?php
							    if ($goodsItem['isByOrder'] == 1) {
								    echo '<p class="b0-price b0-price-related">Ожидается поступление</p>';
							    }
                                elseif ($goodsItem['isSpecial'] == 1) {
								    echo '<p class="b0-price b0-price-related uk-text-danger">Специальная цена: ' . render_price($goodsItem['priceSpecial']) . '</p>';
								    echo '<p class="b0-price b0-price-related">Цена: <del>' . render_price($goodsItem['priceGeneral']) . '</del></p>';
							    }
							    else {
								    echo '<p class="b0-price b0-price-related">Цена: ' . render_price($goodsItem['priceGeneral']) . '</p>';
							    }
							    ?>
                            </div>
                        </div>
                    </div>
                </li>
		    <?php endforeach; ?>
        </ul>
	<?php }
}