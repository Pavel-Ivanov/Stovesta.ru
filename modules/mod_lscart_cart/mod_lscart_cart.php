<?php

defined('_JEXEC') or die();

require_once __DIR__.'/helper.php';

$cart_count = ModLscartCartHelper::getCartCount();
$layout = $params->get('layout', 'default');
require_once JModuleHelper::getLayoutPath('mod_lscart_cart', $layout);
