<?php
defined('_JEXEC') or die();
JImport('b0.fixtures');

class LscartViewOrder extends JViewLegacy
{
	public function display($tmpl = null) {
		$this->item = $this->get('Item');
		$this->addToolBar();
		$this->setDocument();

		parent::display($tmpl);
	}

	protected function addToolBar() {
		JToolbarHelper::title('Просмотр Заказа');
	}

	protected function setDocument() {
		JFactory::getDocument()->setTitle('Просмотр Заказа');
	}
}
