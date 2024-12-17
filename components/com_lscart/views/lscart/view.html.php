<?php
defined('_JEXEC') or die();
JImport('b0.Cart.Cart');
//JImport('b0.fixtures');

class LscartViewLscart extends JViewLegacy
{
	public object $cart;

	public function display($tpl = null) {
		$cart = JFactory::getApplication()->getUserState('cart');
//        b0debug($cart);
		if(!$cart) {
			parent::display('empty');
			return;
		}

		//вызываем метод модели getItems() -> _getListQuery() -> getListQuery()
		// getItems получает список элементов через вызов getListQuery(), поэтому в модели надо переопределить getListQuery()
		$this->cart = new Cart($this->get('Items'));
		parent::display($tpl);
	}
}
