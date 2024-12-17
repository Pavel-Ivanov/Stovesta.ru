<?php
defined('_JEXEC') or die();
JImport('b0.fixtures');
require_once __DIR__.'/helper.php';

$items = ModB0SpecialItemsHelper::getItems($params);
$layout = $params->get('layout', 'default');
require_once JModuleHelper::getLayoutPath('mod_b0_special_items', 'default');
