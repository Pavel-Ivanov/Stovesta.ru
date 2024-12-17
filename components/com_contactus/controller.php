<?php
defined('_JEXEC') or die();

class ContactusController extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = false)
	{
//		$document	= JFactory::getDocument();
		$vName   = $this->input->getCmd('view', 'add');
		$vFormat = JFactory::getDocument()->getType();
		$view = $this->getView($vName, $vFormat);
		$model = $this->getModel($vName);
		$view->setModel($model, true);
		$extension = 'com_contactus';
		$base_dir = JPATH_BASE."/components/com_contactus";
		$language_tag = 'ru-RU';
		JFactory::getLanguage()->load($extension, $base_dir,  $language_tag, true);	
		$view->display();
	}
}
