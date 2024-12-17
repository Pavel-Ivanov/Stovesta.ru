<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

$value = $this->value;
?>
<?php if(count($value) > 1): ?>
	<dl class="dl-horizontal">
<?php endif; ?>
<?php foreach($value as $i => $val):
    $term = $val['label'];
    $description = $val['url'];
?>
    <dt><?php echo $term; ?></dt>
    <dd><?php echo $description; ?></dd>
    <!--
	<?php if($this->params->get('params.filter_enable')): ?>
		<?php echo FilterHelper::filterButton('filter_' . $this->id, $val['url'], $this->type_id, ($this->params->get('params.filter_tip') ? JText::sprintf($this->params->get('params.filter_tip'), '<b>' . $this->label . '</b>', '<b>' . $val['url'] . '</b>') : NULL), $section, $this->params->get('params.filter_icon', 'funnel-small.png')); ?>
	<?php endif; ?>
    -->
<?php endforeach; ?>
<?php if(count($value) > 1): ?>
	</dl>
<?php endif; ?>