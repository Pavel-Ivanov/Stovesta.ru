<?php
defined('_JEXEC') or die();
JImport('b0.pricehelper');
//JImport('b0.fixtures');

/**
 * @var array $items
 */

/**
 * @var JRegistry $params
 */

if(count($items) === 0){
    if($params->get('norecords')) {
        echo '<div class="uk-h4">' . $params->get('norecords') . '</div>';
    }
    return;
}
?>

<ul class="uk-grid uk-grid-width-medium-1-2 uk-grid-match" data-uk-grid-margin data-uk-grid-match="{target:'.uk-panel'}">
	<?php foreach ($items as $item):?>
        <li>
            <div class="uk-panel uk-panel-box">
                <div class="uk-grid">
                    <div class="uk-width-1-3">
						<?= $item['image']?>
                    </div>
                    <div class="uk-width-2-3">
                        <p class="lrs-article-title-related">
							<?= $item['url'] ?>
                        </p>
                        <hr>
                        <p class="lrs-price-related">
							<?php
                            if ($item['isSpecial'] === 1) {
								echo '<span class="uk-text-danger">Специальная цена: ' . render_price($item['priceSpecial']) . '</span>';
								echo '<p>Цена: <del>' . render_price($item['priceGeneral']) . '</del>';
							}
							else {
								echo "Цена: " . render_price($item['priceGeneral']);
							}
							?>
                        </p>
                    </div>
                </div>
            </div>
        </li>
	<?php endforeach;?>
</ul>
