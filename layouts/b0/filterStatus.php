<?php
defined('_JEXEC') or die();

/** @var array $displayData
 */
if (!$displayData) {
	return;
}?>

<button class="uk-button uk-button-mini" type="button" style="background: #ffffff; color: #666666; text-transform: none; border-color: red;"
    data-uk-tooltip title="Сбросить все фильтры"
    onclick="Joomla.submitbutton('records.cleanall')">
	<i class="uk-icon-close uk-margin-right"></i>Очистить все фильтры
</button>
<?php foreach ($displayData['worns'] as $worn): ?>
	<button type="button" class="uk-button uk-button-mini" style="background: #ffffff; color: #666666; text-transform: none; border-color: red;"
        onclick="Cobalt.cleanFilter('<?= $worn->name ?>')" data-uk-tooltip title="Удалить фильтр<br><?= $worn->text ?>">
		<i class="uk-icon-close uk-margin-right"></i><?= $worn->label. ': <span class="uk-text-bold">' . $worn->text . '</span>' ?>
	</button>
<?php endforeach; ?>
