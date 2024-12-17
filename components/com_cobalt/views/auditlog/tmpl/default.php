<?php
defined ( '_JEXEC' ) or die ();
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
JHtml::_('dropdown.init');
?>
<div class="page-header">
	<h1>Журнал аудита</h1>
</div>

<form action="<?= JRoute::_('index.php?option=com_cobalt&view=auditlog&Itemid='.JFactory::getApplication()->input->getInt('Itemid')); ?>" method="post" name="adminForm" id="adminForm">

	<div class="controls controls-row">
		<div class="input-append pull-left">
			<input type="text" size="16" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" />
			<button class="btn" type="submit" rel="tooltip" data-original-title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
				<?= HTMLFormatHelper::icon('magnifier.png');  ?>
			</button>
			<?php if($this->state->get('filter.search')) :?>
			<button class="btn<?= ($this->state->get('filter.search') ? ' btn-warning' : NULL); ?>" type="button" onclick="Cobalt.setAndSubmit('filter_search', '');" rel="tooltip" data-original-title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
				<?= HTMLFormatHelper::icon('eraser.png');  ?>
			</button>
			<?php endif; ?>
			<button class="btn<?= (($this->state->get('auditlog.section_id') || $this->state->get('auditlog.type_id')
                || $this->state->get('auditlog.event_id') || $this->state->get('auditlog.user_id')
                || ($this->state->get('auditlog.fce') && $this->state->get('auditlog.fcs'))) ? ' btn-warning' : NULL); ?>" type="button" data-toggle="collapse" data-target="#filters-block">
				<?= HTMLFormatHelper::icon('funnel.png');  ?>
				<span class="caret"></span>
			</button>
		</div>
	</div>

	<div class="collapse fade" id="filters-block">
		<br>
		<div class="tabbable">
			<button class="btn pull-right btn-primary" type="submit">
				Поиск
			</button>
			<ul class="nav nav-tabs" id="filter-tabs">
				<?php if($this->sections):?>
					<li>
                        <a href="#section" data-toggle="tab">
                            <?php if($this->state->get('auditlog.section_id')):?>
                                <?= HTMLFormatHelper::icon('exclamation-diamond.png', JText::_('AL_FAPPLIED'));  ?>
                            <?php endif;?>
                            Разделы
                        </a>
                    </li>
				<?php endif;?>

				<?php if($this->types):?>
					<li>
                        <a href="#type" data-toggle="tab">
                            <?php if($this->state->get('auditlog.type_id')):?>
                                <?= HTMLFormatHelper::icon('exclamation-diamond.png', JText::_('AL_FAPPLIED'));  ?>
                            <?php endif;?>
                            Типы
                        </a>
                    </li>
				<?php endif;?>

				<?php if($this->events):?>
					<li>
                        <a href="#event" data-toggle="tab">
                            <?php if($this->state->get('auditlog.event_id')):?>
                                <?= HTMLFormatHelper::icon('exclamation-diamond.png', JText::_('AL_FAPPLIED'));  ?>
                            <?php endif;?>
                            События
                        </a>
                    </li>
				<?php endif;?>

				<?php if($this->users):?>
					<li>
                        <a href="#user" data-toggle="tab">
                            <?php if($this->state->get('auditlog.user_id')):?>
                                <?= HTMLFormatHelper::icon('exclamation-diamond.png', JText::_('AL_FAPPLIED'));  ?>
                            <?php endif;?>
                            Пользователи
                        </a>
                    </li>
				<?php endif;?>

				<li><a href="#date" data-toggle="tab">
                        <?php if($this->state->get('auditlog.fce') && $this->state->get('auditlog.fcs')):?>
                            <?= HTMLFormatHelper::icon('exclamation-diamond.png', JText::_('AL_FAPPLIED'));  ?>
                        <?php endif;?>
                        Даты
                    </a>
				</li>
			</ul>

			<div class="tab-content">
				<?php _show_list_filters($this->sections, 'section', $this->state);?>
				<?php _show_list_filters($this->types, 'type', $this->state);?>
				<?php _show_list_filters($this->events, 'event', $this->state);?>
				<?php _show_list_filters($this->users, 'user', $this->state);?>

				<div class="tab-pane" id="date">
					<div class="container-fluid">
						<?php if($this->mtime):?>
							<div class="row-fluid">
								<p><?= JText::sprintf('CALSTARTED', $this->mtime)?></p>
							</div>
						<?php endif;?>
						<div class="row-fluid">
							<div class="pull-left">
								<label>От</label>
								<?= JHtml::calendar((string)$this->state->get('auditlog.fcs'), 'filter_cal_start', 'fcs')?>
							</div>
							<div class="pull-right">
								<label>До</label>
								<?= JHtml::calendar((string)$this->state->get('auditlog.fce'), 'filter_cal_end', 'fce')?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<script>
		  jQuery(function () {
		    jQuery('#filter-tabs a:first').tab('show');
		  })
		</script>
	</div>
	<div class="clearfix"></div>

	<?php if($this->state->get('auditlog.section_id') || $this->state->get('auditlog.type_id')
			|| $this->state->get('auditlog.event_id') || $this->state->get('auditlog.user_id')
			|| ($this->state->get('auditlog.fce') && $this->state->get('auditlog.fcs'))): ?>
		<div class="alert alert-warning">
			<a class="close" data-dismiss="alert" href="#">X</a>
			<p>
                <?= HTMLFormatHelper::icon('exclamation-diamond.png', JText::_('AL_FAPPLIED'));  ?> <?= JText::_('AL_FILTERS')?>
            </p>
			<button type="button" class="btn btn-warning btn-mini" onclick="Joomla.submitbutton('auditlog.reset')">
                <?= JText::_('AL_RESET')?>
            </button>
		</div>
	<?php endif;?>

	<?php if($this->items): ?>
		<table class="uk-table uk-margin-top">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Событие</th>
                    <th>Вер.</th>
                    <th>
                        <?= JHtml::_('grid.sort',  'CRECORD', 'r.title', $listDirn, $listOrder); ?>
                    </th>
                    <th nowrap="nowrap">
                        <?= JHtml::_('grid.sort',  'CCREATED', 'al.ctime', $listDirn, $listOrder); ?>
                    </th>
                    <th nowrap="nowrap">
                        <?= JHtml::_('grid.sort',  'CEVENTER', 'u.username', $listDirn, $listOrder); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item):?>
                    <?php $params = json_decode($item->params);?>
                    <tr class=" <?= $k = 1 - @$k?>" id="row-<?= $item->id ?>">
                        <td>
                            <?= $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td nowrap="nowrap">
                            <?= JText::_($this->type_objects[$item->type_id]->params->get('audit.al'.$item->event.'.msg', 'CAUDLOG'.$item->event));?>
                            <a onclick="Cobalt.checkAndSubmit('#fevent<?php echo $item->event; ?>', <?php echo $item->section_id; ?>)" href="javascript:void(0);" rel="tooltip" data-original-title="<?php echo JText::_('CFILTERTIPEVENTS')?>">
                                <?php echo HTMLFormatHelper::icon('funnel-small.png');  ?></a>

                            <?php IF($item->event == ATlog::REC_TAGNEW || $item->event == ATlog::REC_TAGDELETE):?>
                                <br />
                                <small>
                                    <span class="label">
                                        <?php settype($params->new, 'array'); echo implode('</span>, <span class="label">', $params->new);?>
                                    </span>
                                </small>
                            <?php endif;?>
                            <?php IF($item->event == ATlog::REC_FILE_DELETED || $item->event == ATlog::REC_FILE_RESTORED):?>
                                <br />
                                <small>
                                    <?php if(!empty($params->field)):?>
                                        <span class="label"><?= $params->field;?></span>
                                    <?php endif;?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_cobalt&task=files.download&id='.@$params->file->id.'&fid='.$params->field_id.'&rid='.$item->record_id) ?>">
                                        <?php echo @$params->file->realname;?></a>
                                </small>
                            <?php endif;?>
                        </td>
                        <td>
                            <span class="uk-badge uk-badge-notification">
                                v.<?= (int) @$params->version;?>
                            </span>
                        </td>
                        <td class="has-context">
                            <?php ob_start ();?>

                            <?php if($item->event == ATlog::REC_FILE_DELETED):?>
                                <a href="<?= Url::task('records.rectorefile', $item->record_id.'&fid='.$params->file->id.'&field_id='.$params->file->field_id)?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Восстановить файл">
                                    <?= HTMLFormatHelper::icon('universal.png');  ?>
                                </a>
                            <?php endif;?>
                            <?php IF($item->event == ATlog::REC_NEW):?>
                                <a href="<?= Url::task('records.delete', $item->record_id)?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Удалить">
                                    <i class="uk-icon-times"></i>
                                </a>
                            <?php endif;?>
                            <?php if($item->event == ATlog::REC_PUBLISHED || ($item->event == ATlog::REC_NEW && @$params->published == 1)):?>
                                <a href="<?= Url::task('records.sunpub', $item->record_id); ?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Снять с публикации">
                                    <?= HTMLFormatHelper::icon('cross-circle.png');  ?>
                                </a>
                            <?php endif;?>
                            <?php if($item->event == ATlog::REC_UNPUBLISHED || ($item->event == ATlog::REC_NEW && @$params->published == 0)):?>
                                <a href="<?= Url::task('records.spub', $item->record_id); ?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Опубликовать">
                                    <i class="uk-icon-check uk-text-success"></i>
                                </a>
                            <?php endif;?>
                            <?php if($item->event == ATlog::REC_EDIT && $this->type_objects[$item->type_id]->params->get('audit.versioning')):?>
                                <a href="<?= $url = 'index.php?option=com_cobalt&view=diff&record_id=' . $item->record_id . '&version=' . ($params->version) . '&return=' . Url::back(); ?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Сравнить с версией v.<?= $params->version - 1 ?>">
                                    <i class="uk-icon-sliders"></i>
                                </a>
                                <a href="<?= Url::task('records.rollback', $item->record_id.'&version='.($params->version - 1)); ?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Откатить на версию v.<?= $params->version - 1 ?>">
                                    <i class="uk-icon-undo"></i>
                                </a>
                            <?php endif;?>
                            <?php if(!$item->isrecord):?>
                                <a href="<?= Url::task('records.restore', $item->record_id)?>"
                                   class="uk-button uk-button-link uk-button-small"
                                   data-uk-tooltip title="Восстановить" >
                                    <?= HTMLFormatHelper::icon('universal.png');  ?></a>
                            <?php endif;?>

                            <?php $controls = ob_get_contents();?>
                            <?php ob_end_clean()?>

                            <?php if(trim($controls)):?>
<!--                                <div class="btn-group pull-right" style="display: none;">-->
                                <div class="uk-button-group pull-right">
                                    <?= $controls;?>
                                </div>
                            <?php endif;?>

                            <?php if($item->isrecord):?>
                                <span class="label label-inverse">
                                    <?= $item->record_id  ?>
                                </span>
                                <a href="<?= JRoute::_(Url::record($item->record_id));?>">
                                    <?= $params->title;?>
                                </a>
                            <?php else:?>
                                <?= $params->title;?>
                            <?php endif;?>

                            <a href="javascript:void(0);" onclick="Cobalt.setAndSubmit('filter_search', 'rid:<?= $item->record_id;?>');" data-uk-tooltip title="Показать все записи с этой статьей">
                                <i class="uk-icon-filter uk-icon-hover"></i>
                            </a>
                            <div>
                                <small>
                                    Тип:
                                    <a href="#" rel="tooltip" data-original-title="Показать все записи по этому типу"
                                            onclick="Cobalt.checkAndSubmit('#ftype<?= $item->type_id; ?>', <?= $item->type_id; ?>)">
                                        <?= @$params->type_name;?>
                                    </a>
                                    | Раздел:
                                    <a href="#" rel="tooltip" data-original-title="Показать все записи по этому разделу"
                                            onclick="Cobalt.checkAndSubmit('#fsection<?= $item->section_id; ?>', <?= $item->section_id; ?>)">
                                        <?= @$params->section_name;?>
                                    </a>
                                    <?php if(!empty($params->categories)): ?>
                                        | Категория:
                                        <?php foreach($params->categories as $cat):?>
                                            <?= $cat;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </small>
                            </div>
                        </td>
                        <td nowrap>
                            <?= $item->date;?>
                        </td>
                        <td nowrap>
                            <?= $item->username;?>
                            <a onclick="Cobalt.checkAndSubmit('#fuser<?= $item->user_id; ?>', <?= $item->section_id; ?>)" href="javascript:void(0);" data-uk-tooltip title="Показать все записи журнала по этому пользователю">
                                <i class="uk-icon-filter uk-icon-hover"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
	<?php else: ?>
		<div class="alert alert-warning">
            В журнале нет записей
        </div>
	<?php endif; ?>

	<div class="uk-text-center">
		<small>
			<?php if($this->pagination->getPagesCounter()):?>
				<?= $this->pagination->getPagesCounter(); ?>
			<?php endif;?>
			<?= str_replace('<option value="0">'.JText::_('JALL').'</option>', '', $this->pagination->getLimitBox());?>
			<?= $this->pagination->getResultsCounter(); ?>
		</small>
	</div>
	<div  class="uk-text-center" class="pagination">
		<?= str_replace('<ul>', '<ul class="pagination-list">', $this->pagination->getPagesLinks()); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?= $listDirn; ?>" />
	<?= JHtml::_('form.token'); ?>
</form>

<?php
function _show_list_filters($list, $name, $state)
{
	$cols = 3;
	$i = 0;
?>
	<?php if($list):?>
		<div class="tab-pane" id="<?= $name;?>">
			<div class="container-fluid">
				<?php foreach ($list AS $item): ?>
					<?php if($i % $cols == 0):?>
					<div class="row-fluid">
					<?php endif;?>
						<div class="span4">
							<label class="checkbox">
								<input id="f<?= $name.$item->value?>" type="checkbox" <?= in_array($item->value, (array)$state->get('auditlog.'.$name.'_id')) ? 'checked="checked"' : NULL;?> name="filter_<?= $name?>[]" value="<?= $item->value;?>">
								<?= $item->text;?>
							</label>
						</div>
					<?php if($i % $cols == ($cols - 1)):?>
					</div>
					<?php endif;$i++;?>
				<?php endforeach;?>
				<?php if($i % $cols != 0):?>
				</div>
				<?php endif;?>
			</div>
		</div>
	<?php endif;?>
<?php }?>