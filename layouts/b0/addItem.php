<?php
defined('_JEXEC') or die();

/**
 * @var array $displayData
 * $displayData['section'] - секция
 * $displayData['category'] - категория
 * $displayData['postButtons'] - массив
 * $displayData['contentType'] - название типа контента
 */

if (!$displayData) {
	return;
}

$submit = array_values($displayData['postButtons']);
$submit = array_shift($submit);
?>
<li>
    <a href="<?= Url::add($displayData['section'], $submit, $displayData['category']) ?>">
        <i class="uk-icon-plus"></i>&nbsp;Добавить <?= $displayData['typeName'] ?>
    </a>
</li>