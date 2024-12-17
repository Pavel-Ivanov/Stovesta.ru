<?php
defined('_JEXEC') or die();

$params = $this->params;
?>
<style>
	.default_mlsvalues .alert {
		margin-bottom: <?= $params->get('params.max_values') > 1 ? '10' : '0'?>px;
	}
</style>

<script>

let Mls<?= $this->id; ?> = {};
let allowed<?= $this->id; ?> = <?= $params->get('params.max_levels');?>;

(function($){

	Mls<?= $this->id; ?>.edit = function(el, level, parent_id) {
		let select = $(el).parent('div.pull-left.form-inline').children('select');
		if(select.val() === 0){
			Cobalt.fieldError('<?= $this->id; ?>', '<?= htmlentities(JText::_('MLS_CHOSESOMETHING'), ENT_QUOTES, 'UTF-8') ?>');
			return;
		}
		let text = select.children('option:selected').text();
		text = text.replace(/ \([0-9]*\)$/g, '');

		$('#mls-<?= $this->id; ?>-level'+level+', #add-button, button.btn-edit').hide();

		let input = $(document.createElement('input'))
			.attr({type:'text','rel':select.val()})
			.val(text)
			.bind('keyup', function(event){
				if(event.keyCode === 13){
	        		Mls<?= $this->id; ?>.editValue(level, parent_id);
				}
				if(event.keyCode === 27){
	        		Mls<?= $this->id; ?>.deleteFormEdit(level);
				}
			});

		$(document.createElement('span'))
			.attr('id', 'new-form')
			.addClass('input-append')
			.append(input)
			.append('<a class="btn btn-primary" onclick="Mls<?= $this->id; ?>.editValue(' + level + ', ' + parent_id + ')"><img src="<?= JUri::root(true)?>/media/mint/icons/16/plus.png"></a>')
			.append('<a class="btn btn-danger" onclick="Mls<?= $this->id; ?>.deleteFormEdit('+level+')"><img src="<?= JUri::root(true)?>/media/mint/icons/16/minus.png"></a>')
			.appendTo($("#mls-<?= $this->id; ?>-container"+level));

		input.focus(function(){
			$(this).select();
		}).focus();

	}
	Mls<?= $this->id; ?>.editValue = function(level, parent_id)
	{
		let text = $('#new-form').children('input').val();
		$.ajax({
			url: Cobalt.field_call_url,
			type:"post",
			dataType: 'json',
			data:{
				field_id: <?= $this->id ?>,
				func: "_editvalue",
				name: text,
				mlsid: $('#new-form').children('input').attr('rel'),
				level: level,
				parent_id: parent_id
			}
		}).done(function(json) {

			if(json.error)
			{
				alert(json.error);
				return;
			}

			Mls<?= $this->id; ?>.deleteFormEdit(level);
			$('#mls-<?= $this->id; ?>-level'+level).children('option:selected').text(text);
		});
	}


	Mls<?= $this->id; ?>.checkForm = function(e) {
		if(<?= (int)$params->get('params.max_values')?> <= 0) {
			return;
		}
		if(<?= ($params->get('params.max_values') == 1) ? 1 : 0 ?>){
			return;
		}

		let length = $('#mlsvalues-list<?= $this->id; ?>').children('div.alert').length;


		e = e || [];
		if(e.type === 'closed') {
			length -= 1;
		}

		if(<?= (int)$params->get('params.max_values')?> 	<= length) {
			$('#mls-<?= $this->id; ?>-form-box').css('display', 'none');
			$("#mls-<?= $this->id; ?>-input").remove();
			$.each($('#mls-<?= $this->id; ?>-levels').children('div.form-inline'), function(k, v){
				if((k + 1) > 1) {
					$(v).remove();
				} else {
					$('#mls-44-level1').attr('name','overlimit');
				}
			});
		} else {
			$('#mls-44-level1').attr('name','jform[fields][44][levels][]');
			$('#mls-<?= $this->id;?>-form-box').css('display', 'block');
		}
	}

	Mls<?= $this->id; ?>.addItem = function() {
		let fields 	= $('[name^="jform\\[fields\\]\\[<?= $this->id; ?>\\]\\[levels\\]"]');
		let added 	= {};
		let title 	= [];
		let ids 	= [];
		let noval 	= 0;
		try {
			$.each(fields, function(key, val){
				if(parseInt(this.value) > 0)
				{
					ids[key] = this.value;
					added[this.value] = title[key] = this.options[this.selectedIndex].text.replace(/(?: \([0-9]+\))$/, '');
				}
				else
				{
					noval ++;
				}
			});

			<?php if($params->get('params.min_levels_req')):?>
                if(<?= $params->get('params.min_levels_req');?> > ids.length) {
                    throw '<?= JText::_('MLS_LEVELREQUIRED');?>';
                }
			<?php endif;?>

			if(ids.length)
			{
				added = JSON.stringify(added);

				$.each($('[name^="jform\\[fields\\]\\[<?= $this->id; ?>\\]"]'), function(key, val){
					if(key === 'levels') return true;
					if(this.value === added) {
						throw '<?= JText::_('MLS_VALUEEXISTS');?>';
					}
				});

				let el = $(document.createElement('div'))
					.attr('class', 'alert alert-info')
					.html('<a class="close" data-dismiss="alert" href="#">x</a>' +
						title.join('<?= $this->params->get('params.separator', ' ');?> ') +
						'<input type="hidden" name="jform[fields][<?= $this->id;?>][]" value="'+added.replace(/"/g, '&quot;')+'">')
					.bind('closed', Mls<?= $this->id; ?>.checkForm);

				$('#mlsvalues-list<?= $this->id; ?>').append(el);
				//$('#mls-9-container1').children('select').val('');

				Mls<?= $this->id; ?>.checkForm();

				//Mls<?= $this->id; ?>.getChildren(-1, 2);
			}
		}
		catch(e)
		{
			alert(e);
		}
	}

	Mls<?= $this->id; ?>.getChildren = function(parent_id, level)
	{

		$("#mls-<?= $this->id; ?>-input").remove();
		$.each($('#mls-<?= $this->id; ?>-levels').children('div'), function(k, v){
			if((k + 1) >= level) {
				//this.value = -1;
				//console.log(this)
				$(this).remove();
			}
		});

		if(parent_id === 0) return;
		$.ajax({
			url: Cobalt.field_call_url,
			dataType: 'json',
			type: 'POST',
			data:{
				field_id: <?= $this->id ?>,
				func: "_drawList",
				filter: null,
				level: level,
				parent_id: parent_id
			}
		}).done(function(json) {
			if(!json.success) {
				alert(json.error);
				return;
			}

			$("#mls-<?= $this->id; ?>-container"+level).remove();
			$("#mls-<?= $this->id; ?>-levels").append(
				$(document.createElement('div'))
					.attr({
						'id': "mls-<?= $this->id; ?>-container"+level,
						'class': "pull-left form-inline",
						'style':'margin-right:15px; margin-bottom:5px'
					})
					.html(json.result)
			);
		});
	}

	Mls<?= $this->id; ?>.renderInput = function(level, parent_id)
	{
		$("#mls-<?= $this->id; ?>-input").remove();
		$.each($('#mls-<?= $this->id; ?>-levels').children('div'), function(k, v){
			if((k + 1) > level) {
				$(this).remove();
			}
		});
  
		$('#mls-<?= $this->id; ?>-level'+level+', #add-button, button.btn-edit').hide();

		let input = $(document.createElement('input'))
			.attr({type:'text'})
			.bind('keyup', function(event){
				if(event.keyCode === 13){
	        		Mls<?= $this->id; ?>.addValue(level, parent_id);
				}
				if(event.keyCode === 27){
	        		Mls<?= $this->id; ?>.deleteForm(level);
				}
			});

		$(document.createElement('span'))
			.attr('id', 'new-form')
			.addClass('input-append')
			.append(input)
			.append('<a class="btn btn-primary" onclick="Mls<?= $this->id; ?>.addValue(' + level + ', ' + parent_id + ')"><img src="<?= JUri::root(true)?>/media/mint/icons/16/plus.png"></a>')
			.append('<a class="btn btn-danger" onclick="Mls<?= $this->id; ?>.deleteForm('+level+')"><img src="<?= JUri::root(true)?>/media/mint/icons/16/minus.png"></a>')
			.appendTo($("#mls-<?= $this->id; ?>-container"+level));
	}

	Mls<?= $this->id; ?>.deleteForm = function(level)
	{
		$("#mls-<?= $this->id; ?>-level"+level).val('');
		$("#new-form").remove();
		$('#mls-<?= $this->id; ?>-level'+level+', #add-button, button.btn-edit').show();
	}
	Mls<?= $this->id; ?>.deleteFormEdit = function(level)
	{
		$("#new-form").remove();
		$('#mls-<?= $this->id; ?>-level'+level+', #add-button, button.btn-edit').show();
	}

	Mls<?= $this->id; ?>.addValue = function(level, parent_id)
	{
		$.ajax({
			url: Cobalt.field_call_url,
			type:"post",
			dataType: 'json',
			data:{
				field_id: <?= $this->id ?>,
				func: "_savenew",
				name: $('#new-form').children('input').val(),
				level: level,
				parent_id: parent_id
			}
		}).done(function(json) {

			if(json.error)
			{
				alert(json.error);
				return;
			}

			Mls<?= $this->id; ?>.deleteForm(level);


			$.each(json.result, function(k, v){
				if($("#mls-<?= $this->id; ?>-level" + level + ' option[value='+v.id+']').length <= 0) {
					$("#mls-<?= $this->id; ?>-level"+level)
						.prepend('<option value="' + v.id + '">' + v.name + '</option>')
				}
				$("#mls-<?= $this->id; ?>-level"+level).val(v.id);

				if(allowed<?= $this->id; ?> && allowed<?= $this->id; ?> > level) {
					Mls<?= $this->id; ?>.getChildren(v.id, (level + 1));
				}
			});
		});
	}

	$.each($('#mlsvalues-list<?= $this->id; ?>').children('div.alert'), function(k, v){
		$(this).bind('closed', Mls<?= $this->id; ?>.checkForm);
	});
}(jQuery));
</script>
<?php if($params->get('params.max_values') != 1): ?>
	<div class="default_mlsvalues" id="mlsvalues-list<?= $this->id; ?>">
		<?php if (count($this->value)): ?>
			<?php
			foreach ( $this->value as $item ):
				$title = implode($params->get('params.separator', ' '), $item);
				$id = implode('-', array_keys($item));
			?>
			<div class="alert alert-info" id="mlsval-<?= $id;?>">
				<a class="close" data-dismiss="alert" href="#">x</a>
				<?= $title;?>
				<input type="hidden" name="jform[fields][<?= $this->id;?>][]" value="<?= htmlentities(json_encode($item), ENT_QUOTES, 'UTF-8');?>">
			</div>
			<?php endforeach;?>
		<?php endif; ?>
	</div>
<?php endif; ?>


<div id="mls-<?= $this->id;?>-form-box">
	<?php if($params->get('params.max_values') > 1): ?>
		<small>
			<?= JText::sprintf('F_OPTIONSLIMIT', $params->get('params.max_values')); ?>
		</small>
		<br><br>
	<?php endif;?>

	<div id="mls-<?= $this->id; ?>-levels">
		<?php if($params->get('params.max_values') == 1 && $this->value): ?>
			<?php
			$k = $parent = 1;
			foreach ( $this->value[0] as $id => $name ):
			?>
			<div id="mls-<?= $this->id; ?>-container<?= $k;?>" class="pull-left form-inline" style="margin-right:15px;margin-bottom: 5px;">
				<?= $this->_drawList(array('parent_id' => $parent, 'level' => $k, 'selected' => $id, 'filter' => 0)); ?>
			</div>
			<?php $parent = $id; $k++; endforeach;?>
			<?php if($this->params->get('params.max_levels') >= $k):?>
				<div id="mls-<?= $this->id; ?>-container<?= $k;?>" class="pull-left form-inline" style="margin-right:15px;margin-bottom: 5px;">
					<?= $this->_drawList(array('parent_id' => $parent, 'level' => $k, 'filter' => 0)); ?>
				</div>
			<?php endif;?>
		<?php else:?>
			<div id="mls-<?= $this->id; ?>-container1" class="pull-left form-inline" style="margin-right:15px;margin-bottom: 5px;">
				<?= $this->_drawList(array('parent_id' => 1, 'level' => 1, 'filter' => 0)); ?>
			</div>
		<?php endif;?>
	</div>

	<?php if($params->get('params.max_values') > 1): ?>
		<input type="button" id="add-button" class="btn btn-small btn-warning"
			value="<?= JText::_('MLS_ADD');?>"
			onclick="Mls<?= $this->id; ?>.addItem();">
	<?php endif;?>
</div>
<script type="text/javascript">
	Mls<?= $this->id; ?>.checkForm();
	jQuery('#mlsvalues-list<?= $this->id; ?> div.alert').bind('closed', Mls<?= $this->id; ?>.checkForm);
</script>
