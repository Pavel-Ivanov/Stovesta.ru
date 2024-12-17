<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class ContactusModelModules extends JModelLegacy
{	
	function getModules(){		

		$query= "SELECT * FROM #__modules  WHERE `module` = 'mod_contactus'";
		$this->_db->setQuery($query);
		$this->modules = $this->_db->loadObjectList();
		return $this->modules;
	}
	
}
