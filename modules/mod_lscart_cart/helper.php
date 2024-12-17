<?php
defined('_JEXEC') or die();

class ModLscartCartHelper
{
	static public function getCartCount() {
		$cart = JFactory::getApplication()->getUserState('cart');
		return isset($cart) ? count($cart) : 0;
	}
}