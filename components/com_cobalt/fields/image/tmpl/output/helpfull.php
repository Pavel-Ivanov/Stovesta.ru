<?php
    defined('_JEXEC') or die();
    $image = new JRegistry($this->value);
?>
<?php $url = JUri::root(TRUE).'/'.$this->value['image'];?>

<img itemprop="image" src="<?= $url;?>"
     class="ls-helpfull-image"
	width="400" height="300"
	alt="<?php echo htmlspecialchars($image->get('image_title', $record->title), ENT_COMPAT, 'UTF-8');?>"
	title="<?php echo htmlspecialchars($image->get('image_title', $record->title), ENT_COMPAT, 'UTF-8');?>"
>