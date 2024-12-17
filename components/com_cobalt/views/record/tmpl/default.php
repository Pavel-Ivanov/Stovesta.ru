<?php 
defined('_JEXEC') or die();
?>

<div class="contentpaneopen">
	<?= $this->loadTemplate('record_'.$this->menu_params->get('tmpl_article', $this->type->params->get('properties.tmpl_article', 'default'))) ?>
</div>
