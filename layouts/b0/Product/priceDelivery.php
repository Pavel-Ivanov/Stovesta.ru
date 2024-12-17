<?php
defined('JPATH_BASE') or die();
/**
 * @var object $displayData
 */

?>
<?php if ($displayData->isSpecial):?>
    <?php $delta = (int) $displayData->priceGeneral['value'] - (int) $displayData->priceSpecial['value'];?>
    <p class="b0-price b0-price-first uk-text-danger">
        <?= 'Специальная цена : ' . $displayData->priceSpecial['result'] ?>
    </p>
    <p class="b0-price b0-price-second">
        <?= 'Цена : ' . $displayData->priceGeneral['result'] ?>
    </p>
    <p>
        Вы экономите <?= number_format($delta, 0, '.', ' ') ?> руб.
    </p>
<?php else:?>
    <?php $delta = (int) $displayData->priceGeneral['value'] - (int) $displayData->priceDelivery['value'];?>
    <?php if ($delta > 0): ?>
        <p class="b0-price b0-price-first uk-text-danger">
            <?= 'Цена при доставке : ' . $displayData->priceDelivery['result'] ?>
        </p>
        <p class="b0-price b0-price-second">
            <?= 'Цена : ' . $displayData->priceGeneral['result'] ?>
        </p>
        <p>
            Вы экономите <?= number_format($delta, 0, '.', ' ') ?> руб.
        </p>
    <?php else : ?>
        <p class="b0-price b0-price-first">
            <?= 'Цена: ' . $displayData->priceDelivery['result'] ?>
        </p>
    <?php endif; ?>
<?php endif;?>
