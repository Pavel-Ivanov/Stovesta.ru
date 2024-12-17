<?php
defined('_JEXEC') or die();
$image = new JRegistry($this->value);
/** @var stdClass $record */
?>
<a href="<?= JRoute::_(Url::record($record))?>" target="_blank">
    <img src="<?= JUri::root(TRUE).'/'.$this->value['image']?>"
        alt="<?= htmlspecialchars($image->get('image_title', $record->title), ENT_COMPAT, 'UTF-8')?>"
        title="<?= htmlspecialchars($image->get('image_title', $record->title), ENT_COMPAT, 'UTF-8')?>">
</a>
