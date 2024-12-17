<?php
defined('_JEXEC') or die();
/** @var JRegistry $image */
$image = new JRegistry($this->value);
/** @var JRegistry $params */
$params = $this->params;
/** @var StdClass $record */
?>
<?php $url = JUri::root(TRUE).'/'.$this->value['image'];?>
    <img src="<?= $url;?>" class="uk-align-medium-right"
         width="<?= $params->get('thumbs_width', 400);?>" height="<?= $params->get('thumbs_height', 300);?>"
         alt="<?= $image->get('image_title', $record->title);?>"
         title="<?= $image->get('image_title', $record->title);?>"
    >
