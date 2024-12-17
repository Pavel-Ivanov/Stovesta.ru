<?php
defined('_JEXEC') or die();
if(!$this->items) {
	return;
}
JImport('b0.Sparepart.Spareparts');

/** @var JRegistry $params */
//$params = $this->tmpl_params['list'];
$linkDelivery = $this->tmpl_params['list']->get('tmpl_core.link_delivery') ?? '';
$spareparts = new Spareparts($this->items, $this->tmpl_params['list']);
?>

<ul class="uk-grid uk-grid-width-medium-1-2 uk-grid-match" data-uk-grid-margin data-uk-grid-match="{target:'.uk-panel'}">
	<?php foreach ($spareparts->items as $id => $item) :?>
        <li>
            <div class="uk-panel uk-panel-box">
                <div class="b0-title-related">
                    <a href="<?= JRoute::_($item->url)?>" title="<?= $item->title?>" target="_blank">
						<?= $item->title?>
                    </a>
                </div>
				<?php $spareparts->renderField('p', '', $item->subtitle);?>
                <hr class="uk-article-divider">
                <div class="uk-grid">
                    <div class="uk-width-1-3">
						<?= $item->image ?>
                    </div>
                    <div class="uk-width-2-3">
						<?php $spareparts->renderPriceRelated($item, $linkDelivery);?>
                    </div>
                </div>
            </div>
        </li>
	<?php endforeach;?>
</ul>
