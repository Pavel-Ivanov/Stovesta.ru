<div>
	<div class="row-fluid">
		<h2><?php echo JText::_('COM_CONTACTUS_MODULES_LIST');?></h2> 
		<?php foreach ($this->modules as $module) {
			echo "<a href='index.php?option=com_contactus&view=list&module_id=" . $module->id . "'>" . $module->title . "</a>"."<br>";
		} ?>
	</div>
</div>