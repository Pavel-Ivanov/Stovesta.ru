<?php
defined('JPATH_BASE') or die();
/**
 * @var array $displayData
 */
$delta = (int) $displayData['priceRegular']['value'] - (int) $displayData['priceDiscounted']['value'];
?>
<?php if ($delta > 0) :?>
    <p class="b0-price b0-price-first uk-text-danger">
        <?= $displayData['priceDiscounted']['label'] . ' : ' . $displayData['priceDiscounted']['result'] ?>
    </p>
    <p class="b0-price b0-price-second">
        <del><?= $displayData['priceRegular']['label'] . ' : ' . $displayData['priceRegular']['result'] ?></del>
    </p>
    <p>
        Вы экономите <?= number_format($delta, 0, '.', ' ') ?> руб.
    </p>
<?php else:?>
    <p class="b0-price b0-price-first uk-text-center-small">
        <?//= $displayData['priceRegular']['label'] . ' : ' . $displayData['priceRegular']['result'] ?>
        <?= $displayData['priceDiscounted']['label'] . ' : ' . $displayData['priceDiscounted']['result'] ?>
    </p>
<?php endif;?>
