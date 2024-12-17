<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

class ContactusViewFeedback extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$document	= JFactory::getDocument();
		$document->addStyleSheet('components/com_contactus/css/feedback.css');
		$model= $this->getModel();
		$jinput = JFactory::getApplication()->input;
		$id = $jinput->get('id', '1');
		$module_id = $jinput->get('module_id', '1');
		$feedback=$model->getFeedback($id);
		$info=$model->getInfo($module_id);
		$this->assignRef('feedback', $feedback);
		$this->assignRef('info', $info);
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

		JToolBarHelper::title(JText::_('COM_CONTACTUS_FEEDBACK'));

	}

	
}
