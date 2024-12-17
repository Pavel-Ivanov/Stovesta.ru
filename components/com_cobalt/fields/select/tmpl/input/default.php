<?php
defined('_JEXEC') or die();

if($this->params->get('params.chosen', false)) {
	JHtml::_('formbehavior.chosen', '.cobalt-chosen-'.$this->id);
}

$class = ' class="' . $this->params->get('core.field_class', 'inputbox') . ($this->required ? ' required' : NULL) . '"';
$required = $this->required ? ' required="true" ' : NULL;
$style = ' style="max-width: ' . $this->params->get('params.width', '450') . 'px"';
?>

<select name="jform[fields][<?= $this->id ?>]" class="elements-list cobalt-chosen-<?= $this->id ?>" id="form_field_list_<?= $this->id ?>" <?= $required . $style ?>>
	<option value=""><?= JText::_($this->params->get('params.'.($this->params->get('params.sql_source') ? "sql_" : null).'label', 'S_CHOOSEVALUE')) ?></option>
<?php
$selected = ($this->value ?: $this->params->get('params.selected'));

if(!is_array($this->value)) {
    $this->value = (array) $this->value;
}

foreach($this->values as $key => $line):
	$atr = '';
	if (is_string($line)) {
        $val = explode($this->params->get('params.color_separator', "^"), $line);
    }
	if (isset($val[1])) {
		$atr .= ' style="color:' . $val[1] . '"';
	}

	$v = is_string($line) ? $line : $line->id;
	if ($this->value && in_array($v, $this->value)) {
		$atr .= ' selected="selected"';
	}
	if($this->params->get('params.sql_source')) {
		if($this->value && array_key_exists($line->id, $this->value)) {
			$atr .= ' selected="selected"';
		}
		$value = $line->id;
		$text = $line->text;
	}
	else {
		$value = htmlspecialchars($line, ENT_COMPAT, 'UTF-8');
		$text = JText::_($val[0]);
	} ?>
	<option value="<?= $value ?>" <?= $atr ?>><?= JText::_($text) ?></option>
<?php endforeach; ?>
</select>

<?php if (in_array($this->params->get('params.add_value', 2), $this->user->getAuthorisedViewLevels()) && !$this->params->get('params.sql_source')):?>
	<div class="clearfix"></div>
	<div id="variant_<?= $this->id ?>">
		<a id="show_variant_link_<?= $this->id ?>"
			rel="{field_type:'<?= $this->type ?>', id:<?= $this->id;?>, inputtype:'option', limit:1}"
			href="javascript:void(0)" onclick="Cobalt.showAddForm(<?= $this->id ?>)"><?= JText::_($this->params->get('params.user_value_label', 'Your variant')) ?>
        </a>
	</div>
<?php endif;?>

