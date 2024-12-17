<?php
defined('_JEXEC') or die();

$value = number_format($this->value, $this->params->get('params.decimals_num'),
	$this->params->get('params.dseparator', ''), str_ireplace('_', ' ', $this->params->get('params.separator', ' ')));

echo str_ireplace('_', ' ', $this->params->get('params.prepend') ?? '');
echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
echo str_ireplace('_', ' ', $this->params->get('params.append'));
