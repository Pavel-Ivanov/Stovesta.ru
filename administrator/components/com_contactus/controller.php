<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

class ContactusController extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = false)
	{

		$document	= JFactory::getDocument();
		$jinput = JFactory::getApplication()->input;
		$vName = $jinput->get('view', 'modules');
		$vFormat = $document->getType();
		$view = $this->getView($vName, $vFormat);
		$model = $this->getModel($vName);
		$view->setModel($model, true);
		$view->display();
		
	}
	
	public function	deleteFeed()
	{
		$jinput = JFactory::getApplication()->input;
		$id = $jinput->get('delete_id', 1);
		$db = JFactory::getDbo();
		$query= "DELETE FROM #__contactus_data WHERE `id` = {$id}";
		$db->setQuery($query);
		$result = $db->execute();
	}
}
