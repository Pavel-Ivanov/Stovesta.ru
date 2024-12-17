<?php
defined('_JEXEC') or die();
/** @var StdClass $record */
?>

<img src="<?= JUri::root(TRUE).'/'.$this->value['image'] ?>"
     width="<?= $this->params->get('params.thumbs_width', 400) ?>" height="<?= $this->params->get('params.thumbs_height', 300) ?>"
     alt="<?= $record->title ?>" title="<?= $record->title ?>">
