<?php
defined('_JEXEC') or die();

$fid = JFactory::getApplication()->input->getInt('field_id');
$k = 0;
?>
<style>
	.list-item {
		margin-bottom: 5px;
	}
	#recordslist {
		margin-top: 20px;
	}
</style>

<script>
(function ($) {
	window.closeWindow = function()
    {
		list = $('#recordslist').children('div.alert');
		parent['updatelist<?= $fid?>'](list);
		parent['modal<?= $fid; ?>'].modal('hide');
	};
	window.attachRecord = function(el)
	{
		var id = el.attr('rel');
		var title = el.children('span').text();
		<?php if(JFactory::getApplication()->input->get('mode') == 'form'):?>
			var multi = parent['multi<?= $fid; ?>'];
			var limit = parent['limit<?= $fid; ?>'];
			var inputname = parent['name<?= $fid; ?>'];

			list = $('#recordslist');
			if(!multi) {
				list.html('');
			}
			else {
				lis = list.children('div.alert');
				if(lis.length >= limit) {
					alert('<?= JText::_("CERRJSMOREOPTIONS");?>');
					return false;
				}
				error = 0;
				$.each(lis, function(k, v){
					if($(v).attr('rel') == id){
						alert('<?= JText::_('CALREADYSELECTED');?>');
						error = 1;
					}
				});
				if(error) return false;
			}
			var el = $(document.createElement('div'))
				.attr({
					'class': 'alert alert-info list-item',
					rel: id
				})
				.html('<a class="close" data-dismiss="alert" href="#">x</a><span>'+title+'</span><input type="hidden" name="'+inputname+'" value="'+id+'">')
				.appendTo(list);
		<?php else: ?>
			$.ajax({
				url: Cobalt.field_call_url,
				dataType: 'json',
				type: 'POST',
				data:{
					field_id: <?= JFactory::getApplication()->input->getInt('field_id');?>,
					func:'onAttachExisting',
					field:'<?= JFactory::getApplication()->input->get('type');?>',
					record_id:<?= JFactory::getApplication()->input->getInt('record_id');?>,
					attach:id
				}
			}).done(function(json) {
				if(!json.success)
				{
					alert(json.error);
					return;
				}
				parent.location.reload();
				parent['modal<?= $fid; ?>'].modal('hide');
			});
		<?php endif;?>
	}
}(jQuery));
</script>

<br>
<form name="adminForm" id="adminForm" method="post">
	<div class="container-fluid">
		<div id="row-fluid">
			<div class="pull-left input-append">
				<input type="text" name="filter_search2" id="filter_search2" value="<?= $this->state->get('records.search2'); ?>" />
				<button class="btn" type="submit">
					<?= HTMLFormatHelper::icon('document-search-result.png');  ?>
				<?= JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button class="btn" type="button" onclick="document.id('filter_search2').value='';this.form.submit();">
					<?= HTMLFormatHelper::icon('eraser.png');  ?>
				<?= JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			<?php if(JFactory::getApplication()->input->get('mode') == 'form'):?>
			<div class="pull-right">
				<button type="button" class="btn" onclick="closeWindow()">
					<?= HTMLFormatHelper::icon('tick-button.png');  ?>
				<?= JText::_('CAPPLY');?></button>
            <?php endif;?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="container-fluid">
	<?php if(JFactory::getApplication()->input->get('mode') == 'form'):?>
		<div class="row-fluid">
			<div class="span8">
	<?php endif;?>

    <table class="table">
        <thead>
            <th width="1%"></th>
            <th>Артикул</th>
            <th>Название</th>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $item):?>
                <tr class="cat-list-row<?= $k = 1 - $k; ?>">
                    <td><?= $this->pagination->getRowOffset($i); ?></td>
                    <td><?= ($fid == 61) ? json_decode($item->fields, TRUE)[5] : '';?></td>
                    <td><a href="javascript:void(0)" rel="<?= $item->id?>"><span><?= $item->title?></span></a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <div class="pull-right"><?= $this->pagination->getPagesCounter(); ?></div>
    <div class="pagination">
        <?= $this->pagination->getPagesLinks(); ?>
    </div>
    <script>
        (function($){
            $('a[rel]').on('click', function(){
                attachRecord($(this));
            });
        }(jQuery))
    </script>

	<?php if(JFactory::getApplication()->input->get('mode') == 'form'):?>
			</div>
			<div class="span4">
				<div id="recordslist">
				</div>
			</div>
		</div>
		<script>
			(function($){
				var listofselected = $(parent['elementslist<?= JFactory::getApplication()->input->getInt('field_id')?>'])
				.children('div.alert')
				.each(function(){
					attachRecord($(this));
				});
			}(jQuery))
		</script>
	<?php endif;?>
	</div>

	<input type="hidden" name="option" value="com_cobalt" />
	<input type="hidden" name="section_id" value="<?= JFactory::getApplication()->input->getInt('section_id')?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="limitstart" value="0" />
	<?= JHtml::_( 'form.token' ); ?>
</form>
