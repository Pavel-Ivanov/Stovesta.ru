<?php
defined('_JEXEC') or die();
$url = JUri::root(TRUE).'/'.$this->value['image'];
?>

<img src="<?= $url;?>" class="uk-vertical-align-middle" width="200" alt="<?= $record->title;?>">