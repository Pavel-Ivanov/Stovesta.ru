<?php
defined('_JEXEC') or die();
/**
 * @var array $displayData
 */
if (!$displayData){
	return;
}

if ($displayData['special'] && in_array($displayData['id'], $displayData['special'])) {
	echo '<p><strong>Уточняйте наличие по телефону</strong></p>';
}
elseif (array_sum($displayData['availability']) <= 0) {
	echo '<p><strong>Товар временно отсутствует</strong></p>';
}
else {
	echo '<p><strong>Наличие в магазинах:</strong></p>';
	echo '<ul class="uk-list uk-margin-top-remove">';
	foreach ($displayData['availability'] as $department => $amount){
		if ($amount <= 0) {
			continue;
		}
        if ($amount <= 10) {
            echo '<li><strong>'.$department . '</strong>- ' . $amount.' шт.</li>';
        }
        else {
            echo '<li><strong>'.$department . '</strong>- много</li>';
        }
	}
	echo '</ul>';
}
