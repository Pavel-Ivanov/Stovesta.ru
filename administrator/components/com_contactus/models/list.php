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

class ContactusModelList extends JModelLegacy
{	
	function getList($module_id, $offset){
		$query= "SELECT * FROM #__contactus_data  WHERE `module_id` = {$module_id} ORDER BY id DESC LIMIT {$offset},10";
		$this->_db->setQuery($query);
		$this->list = $this->_db->loadObjectList();
		return $this->list;
	}

	function getInfo($module_id ){	
		$query= "SELECT * FROM #__modules  WHERE `id` = {$module_id}";
		$this->_db->setQuery($query);
		$this->params = $this->_db->loadObject();
		return $this->params;
	}
	
	function getMaxPage($module_id){
		$query= "SELECT count(*) FROM #__contactus_data  WHERE `module_id` = {$module_id}";
		$this->_db->setQuery($query);
		$count = $this->_db->loadResult();
		$MaxPage = ceil($count/10);
		$MaxPage = ceil($count/10) == 0 ? 1 : ceil($count/10);
			
		return $MaxPage;	
	}
	
	
	
}
