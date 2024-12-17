<?php
defined('_JEXEC') or die();

/**
 * @var array $displayData
 * $displayData['section'] - секция
 * $displayData['category'] - категория
 * $displayData['postButtons'] - массив
 */

if (!$displayData) {
	return;
}

foreach ($displayData['postButtons'] as $button): ?>
    <li>
        <a href="<?= Url::add($displayData['section'], $button, $displayData['category']) ?>">
            <i class="uk-icon-plus uk-margin-right"></i>Добавить <?= $button->name ?>
        </a>
    </li>
<?php endforeach;?>
