<?php
defined('_JEXEC') or die('Restricted access');
?>
<style>
	.filter-icon {
		margin-right: 5px;
		line-height: 30px;
	}

	#filter-form input[type="text"][name^="filters"],
	#filter-form input[type="text"][class="cdate-field"],
	#filter-form select {
		box-sizing: border-box;
		margin: 0;
		min-height: 28px;
	}

	#filter-form select {
		margin-bottom: 5px;
	}

	.well.active {
		border: 3px solid;
		position: relative;
	}

	.well.active img.filter-close {
		position: absolute;
		top: -7px;
		right: -7px;
		cursor: pointer;
	}
</style>
<form class="uk-form" action="<?= JRoute::_('index.php'); ?>" method="post" name="filterform" id="filter-form">

<!--		<div class="row-fluid <?/*=($state->get('records.search') ? ' active' : NULL) */?>">
			<input type="text" class="span12" name="filter_search" value="<?/*= $state->get('records.search');*/?>"/>
		</div>
-->
    <div class="uk-form-row">
        <input type="text" name="filter_search" value="<?= $state->get('records.search');?>"/>
    </div>

	<?php if($params->get('filter_category_type')): ?>
		<legend>
			<?php if($params->get('show_icons', 1)): ?>
				<span class="pull-left filter-icon"><?php echo HTMLFormatHelper::icon('category.png'); ?></span>
			<?php endif; ?>
			<?php echo $params->get('category_label'); ?>
		</legend>
		<div class="well well-small<?php echo($state->get('records.category') ? ' active' : NULL) ?>">
			<?php if($params->get('filter_category_type') == 1): ?>
				<?php echo JHtml::_('categories.form', $section, $state->get('records.category'), array('empty_cats' => $params->get('filter_empty_cats', 1))); ?>
			<?php elseif($params->get('filter_category_type') == 2): ?>
				<?php echo JHtml::_('categories.checkboxes', $section, $state->get('records.category'), array('columns' => 3, 'empty_cats' => $params->get('filter_empty_cats', 1))); ?>
			<?php elseif($params->get('filter_category_type') == 3): ?>
				<?php echo JHtml::_('categories.select', $section, $state->get('records.category'), array('multiple' => 0, 'empty_cats' => $params->get('filter_empty_cats', 1))); ?>
			<?php elseif($params->get('filter_category_type') == 4): ?>
				<?php echo JHtml::_('categories.select', $section, $state->get('records.category'), array('multiple' => 1, 'size' => 25, 'empty_cats' => $params->get('filter_empty_cats', 1))); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php foreach($filters as $filter): ?>
		<?php if(in_array($filter->id, (array)$params->get('field_id_exclude', array())))
			continue; ?>

		<?php $f = $filter->onRenderFilter($section, ($params->get('filter_fields_template') == 'section' ? FALSE : TRUE)) ?>

		<?php if(trim($f)): ?>
<!--			<legend>
				<?/*= $filter->label; */?>
				<?php /*if($filter->params->get('params.filter_descr')): */?>
					<small rel="tooltip" data-original-title="<?/*= JText::_($filter->params->get('params.filter_descr')); */?>">
                        <i class="icon-help"></i>
                    </small>
				<?php /*endif; */?>
			</legend>
-->			<div class="uk-form-row<?= ($filter->isFilterActive() ? ' uk-form-danger' : NULL) ?>">
				<?= $f; ?>
				<?php if($filter->isFilterActive()): ?>
                    <img class="filter-close" onclick="Cobalt.cleanFilter('filter_<?= $filter->key ?>')"
                         rel="tooltip" data-original-title="<?= JText::_('CDELETEFILTER') ?>"
                         src="<?= JUri::root(TRUE) ?>/media/mint/icons/16/cross-circle.png">
				<?php endif; ?>
			</div>
		<?php endif; ?>

	<?php endforeach; ?>

	<input type="hidden" name="option" value="com_cobalt">
	<input type="hidden" name="view" value="records">
	<input type="hidden" name="section_id" value="<?= $section->id; ?>">
	<input type="hidden" name="cat_id" value="<?= $cat_id; ?>">
	<?php if($user_id > 0): ?>
		<input type="hidden" name="user_id" value="<?= $user_id; ?>">
	<?php endif; ?>
	<input type="hidden" name="view_what" value="<?= $vw; ?>">
	<input type="hidden" name="task" value="records.filters">
	<input type="hidden" name="limitstart" value="0">

	<div class="form-actions">
		<button type="submit" class="uk-button">
			<?= JText::_('CSEARCH'); ?>
		</button>
	</div>
</form>


