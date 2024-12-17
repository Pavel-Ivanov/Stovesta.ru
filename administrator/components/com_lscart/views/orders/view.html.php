<?php
defined('_JEXEC') or die();

class LscartViewOrders extends JViewLegacy
{
	protected $items;

	public function display($tpl = null) {
		$this->addToolBar();
//		$this->setDocument();

		$this->items = $this->get('Items');    //вызов метода getItems модели

		parent::display($tpl);
	}

	protected function addToolBar() {
		JToolbarHelper::title('Заказы доставки');
	}
}
