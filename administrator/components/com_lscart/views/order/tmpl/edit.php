<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
?>

<form action="<?= JRoute::_('index.php?option=com_lscart&layout=edit&id='.(int)$this->item->id)?>"
      method="post" id="adminForm" name="adminForm" class="form-validate">

	<?= $this->form->renderFieldSet('base'); ?>
	<?= $this->form->getField('id')->renderField();?>
	<input type="hidden" name="task" value="order.edit">
	<?= JHtml::_('form.token');?>

</form>
