<div class="row-fluid">
		<div class="row-fluid">
			<div class="span6">
				<h2><?php echo JText::_('COM_CONTACTUS_FEEDBACK');?></h2>
			</div>	
			<div class="span6">
				<p class="text-right"><a href="<?php echo JURI::base();?>index.php?option=com_contactus&view=list&module_id=<?php echo $this->feedback->module_id?>" class="text-right"><?php echo JText::_('COM_CONTACTUS_BACK_LIST');?></a></p>
			</div>
		</div>
		<div class="row-fluid">
			<table id="ContactusTable" class="table table-striped">
				<tbody>			
				<tr><th>Id</th><td><?php echo $this->feedback->id;?></td></tr>
				<tr><th>Date</th><td><?php echo $this->feedback->created_at;?></td></tr>
				<tr><th>Page</th><td><?php echo $this->feedback->page;?></td></tr>
				<?php 
					$params = json_decode($this->info->params);
					$data = json_decode($this->feedback->data); 
					if (isset($params->field))
						{	
							foreach ($params->field as $field)
								{
									if ($field->type !== "Text")
									{
										$f = $field->name;?>
										<tr><th><?php echo $field->title; ?></th><td><?php if (isset($data->$f)){echo $data->$f;}; ?></td></tr>
								 <?php }
								}		
						}?>	
				</tbody>
			</table>
		</div>
	</div>
</div>