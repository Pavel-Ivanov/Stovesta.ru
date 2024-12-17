<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

class ContactusViewModules extends JViewLegacy
{
	public function display($tpl = null)
	{
		$document	= JFactory::getDocument();
		$document->addStyleSheet('components/com_contactus/css/list.css');
		$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
		$model=$this->getModel();
		$modules=$model->getModules();
		$this->assignRef('modules', $modules);
		
		parent::display($tpl);
		$this->addToolbar();
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{

		JToolBarHelper::title(JText::_('COM_CONTACTUS_MODULES_TITLE'));

	}

	
}
