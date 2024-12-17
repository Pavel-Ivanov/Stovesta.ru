<?php
defined('_JEXEC') or die();

$value = [];
if(is_array($this->value)) {
	foreach ($this->value as $item) {
		$full  = [];
		$title = [];
		foreach ($item as $id => $level) {
			$level  = JText::_($level);
			$full[] = $level;
			if ($this->params->get('params.filter_enable')) {
				$tip = ($this->params->get('params.filter_tip') ? JText::sprintf($this->params->get('params.filter_tip'), '<b>' . $this->label . '</b>', "<b>" . implode($this->params->get('params.separator', ' '), $full) . "</b>") : null);
				switch ($this->params->get('params.filter_linkage')) {
					case 1 :
						$level = FilterHelper::filterLink('filter_' . $this->id, $id, $level, $this->type_id, $tip, $section);
						break;
					case 2 :
						$level = $level . ' ' . FilterHelper::filterButton('filter_' . $this->id, $id, $this->type_id, $tip, $section, $this->params->get('params.filter_icon', 'funnel-small.png'));
						break;
				}
			}
			$title[] = $level;
		}
		$value[] = implode($this->params->get('params.separator', ' '), $title);
	}
}

if(count($value) == 1) {
	echo $value[0];
}
elseif(count($value) > 1) {?>
	<ul>
	  <li><?= implode('</li><li>', $value);?></li>
	</ul>
<?php }?>
