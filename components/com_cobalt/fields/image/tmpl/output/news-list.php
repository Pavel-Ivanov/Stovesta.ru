<?php
defined('_JEXEC') or die();

$image = new JRegistry($this->value);
/**
 * @var object $record
 */
?>

<a href="<?= JRoute::_($record->url) ?>">
    <img src="<?= JUri::root(TRUE).'/'.$this->value['image'];?>"
         width="auto" height="auto"
         alt="<?= htmlspecialchars($image->get('image_title', $record->title), ENT_COMPAT, 'UTF-8');?>"
         title="<?= htmlspecialchars($image->get('image_title', $record->title), ENT_COMPAT, 'UTF-8');?>">
</a>
