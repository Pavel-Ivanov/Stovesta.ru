<?php
defined('_JEXEC') or die();
/**
 * @var object $displayData
 */
if (!$displayData) {
	return;
}
?>
<form method="post" class="uk-margin-bottom-remove">
    <div class="uk-text-center">
        <small>
            <?php if($displayData->getPagesCounter()):?>
                <?= '<span class="uk-margin-right">'. $displayData->getPagesCounter() . '</span>' ?>
            <?php endif;?>
            <?= str_replace(['<option value="0">'.JText::_('JALL').'</option>', '<option value="100">100</option>'], '', $displayData->getLimitBox()) ?>
            <?= '<span class="uk-margin-left">'. $displayData->getResultsCounter() . '</span>' ?>
        </small>
    </div>
    <div class="uk-text-center pagination uk-margin-remove">
        <?= $displayData->getPagesLinks() ?>
    </div>
</form>
