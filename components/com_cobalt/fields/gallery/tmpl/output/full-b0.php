<?php
// Полный вид поля галерея в Альбоме
defined('_JEXEC') or die();

if(empty($this->value)) {
	return null;
}
?>
<div class="uk-grid uk-grid-width-medium-1-4 uk-text-center" data-uk-grid-margin data-uk-grid-match>
    <?php $dir = '/' . JComponentHelper::getParams('com_cobalt')->get('general_upload') . '/' . $this->params->get('params.subfolder') . '/';?>
	<?php foreach($this->value as $picture):  ?>
		<div>
			<?php $path = $dir . $picture['fullpath'];?>
		    <a href="<?= $path;?>" data-lightbox-type="image" data-uk-lightbox="{group:'group1'}" title="<?= $picture['description'];?>">
		        <img src="<?= $path;?>" width="240" height="180" alt="<?= $picture['description'];?>">
		    </a>
            <?php if ($picture['description']) :?>
                <p><?= $picture['description'];?></p>
            <?php endif;?>
		</div>
	<?php endforeach;?>
</div>
