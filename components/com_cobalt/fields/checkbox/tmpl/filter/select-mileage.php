<?php
defined('_JEXEC') or die();

$default = array_shift($this->default);
$list = array();
foreach($this->values as $k => $value)
{
	if (!$value->field_value)
		continue;
	$c = explode('^', $value->field_value);
	ArrayHelper::clean_r($c);
	$label = JText::_(strip_tags($c[0]));

	$list[$k] = new stdClass();
	$list[$k]->text = $label;
	if ($this->params->get('params.filter_show_number', 1))
	{
		$list[$k]->text .= " ({$value->num})";
	}
	$list[$k]->value = $value->field_value;
}

array_unshift($list, JHtml::_('select.option', '', 'Все пробеги'));

echo JHtml::_('select.genericlist', $list, "filters[{$this->key}][value]", null, 'value', 'text', $default);
