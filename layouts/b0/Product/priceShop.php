<?php
defined('JPATH_BASE') or die();
/**
 * @var object $displayData
 */
?>
<?php if ($displayData->isSpecial):?>
    <?php $delta = (int) $displayData->priceGeneral['value'] - (int) $displayData->priceSpecial['value'];?>
    <p class="b0-price b0-price-first uk-text-danger">
        <?= 'Специальная цена: ' . $displayData->priceSpecial['result'] ?>
    </p>
    <p class="b0-price b0-price-second">
        <?= 'Цена: ' . $displayData->priceGeneral['result'] ?>
    </p>
    <p>
        Вы экономите <?= number_format($delta, 0, '.', ' ') ?> руб.
    </p>
<?php elseif ($displayData->isOriginal):?>
    <p class="b0-price b0-price-first uk-text-danger">
        <?= 'Цена: ' . $displayData->priceGeneral['result'] ?>
    </p>
<?php else:?>
    <p class="b0-price b0-price-first uk-text-danger">
        <?= 'Цена по золотой карте: ' . $displayData->priceGold['result'] ?>
    </p>
    <p class="b0-price b0-price-second uk-text-center-small">
        <?= 'Цена без карты: ' . $displayData->priceGeneral['result'] ?>
    </p>
<?php endif;?>
