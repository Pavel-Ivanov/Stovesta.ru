<?php
$params = json_decode($this->info->params);
?>
<div>
	<div class="row-fluid">
		<div class="row-fluid">
			<div class="span6">
				<h2><?php echo JText::_('COM_CONTACTUS_FEEDBACKS_LIST');?></h2> 
			</div>	
			<div class="span6">
				<p class="text-right"><a href="<?php echo JURI::base();?>index.php?option=com_contactus&view=modules" class="text-right"><?php echo JText::_('COM_CONTACTUS_BACK_MODULES');?></a></p>
			</div>
		</div>
		<div class="row-fluid">
			<table id="ContactusTable" class="table "> 
				<thead> 
					<tr> 
						<th>Id</th>
						<th>Date</th>	
						<th>Page</th>	
						<?php 	
							if (isset($params->field))
							{	
								$i = 0;
								foreach ($params->field as $f)
								{ 
									if ($f->type !== "Text")
										{?>
									<th><?php echo $f->title;?></th>
							<?php	$i +=1;}}
							}?>	
						<th></th>	
						<th></th>
					</tr> 
				</thead>
				<tbody>
					<?php if (isset($this->list)){foreach($this->list as $feed){ ?>
						<tr <?php if ($feed->read == 1){echo 'class="read"';};?>>
							<?php 
								echo '<td class="contactus_td_long">'. $feed->id.'</td>';
								echo '<td class="contactus_td_long">'. $feed->created_at.'</td>';
								echo '<td class="contactus_td_long">'. $feed->page.'</td>';
								$data = json_decode($feed->data); 
								if (isset($params->field))
								{	
									foreach ($params->field as $field)
									{
										if ($field->type !== "Text")
										{
											$f = $field->name;
											mb_internal_encoding("UTF-8");?>
											<td class="contactus_td_long"><?php if (isset($data->$f)){echo mb_substr($data->$f,0,30);}; ?></td>
							 		 <?php 
							 		 }
							 		}		
								}?>			
							<td class="contactus-td"><a href="index.php?option=com_contactus&view=feedback&module_id=<?php echo $this->module_id;?>&id=<?php echo $feed->id;?>"><?php echo JText::_('COM_CONTACTUS_VIEW');?></a></td>
							<td class="contactus-td"><i class="fa fa-trash-o delete_class" onclick="delete_feed(this);" id="<?php echo $feed->id;?>"></i></td>
						</tr>
					<?php }}
					?>
					</tr>
				</tbody>	
			</table>	
		</div>	
		<div>
			<ul class="pager">
				<li><a href="index.php?option=com_contactus&view=list&module_id=<?php echo $this->module_id;?>&page=<?php echo $this->PreviousPage;?>" class="previous"><?php echo JText::_('COM_CONTACTUS_PREVIOUS');?></a></li>
				<li><a href="index.php?option=com_contactus&view=list&module_id=<?php echo $this->module_id;?>&page=<?php echo $this->NextPage;?>" class="next"><?php echo JText::_('COM_CONTACTUS_NEXT');?></a></li>
			</ul>
		</div>	
	</div>
</div>
<script>
function delete_feed(feed)
{
	var con = confirm("<?php echo JText::_('COM_CONTACTUS_DELETE_FEED');?>");
	if (con == true){
		del_id = feed.getAttribute("id");
		d = feed.parentNode.parentNode;
		d.parentNode.removeChild(d);
		
		var xhr = new XMLHttpRequest();
		
		var body = 'delete_id='+del_id;

		xhr.open("POST", 'index.php?option=com_contactus&task=deletefeed', true)
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
		xhr.send(body);
	}	
}
var current = <?php echo $this->CurrentPage;?>;
if (current==1){
	var b = document.getElementsByClassName("previous");
	b[0].className = b[0].className + " disabled";
	b[0].onclick = function(){
		return false;
	};
}	
var max = <?php echo $this->MaxPage;?>;
if (current>= max){
	var a = document.getElementsByClassName("next");
	a[0].className = a[0].className + " disabled";
	a[0].onclick = function(){
		return false;
	};
}	
</script>