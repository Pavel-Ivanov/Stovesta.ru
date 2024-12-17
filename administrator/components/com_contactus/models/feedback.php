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

class ContactusModelFeedback extends JModelLegacy
{	

	function getFeedback($id){
		$query= "SELECT * FROM #__contactus_data WHERE id = {$id}";
		$this->_db->setQuery($query);
		$this->feedback = $this->_db->loadobject();
		$this->ReadToDB($id);
		
		return $this->feedback;

	}

	function getInfo($module_id){
			
		$query= "SELECT * FROM #__modules  WHERE `id` = {$module_id}";
		$this->_db->setQuery($query);
		$this->params = $this->_db->loadObject();
		return $this->params;
	}
	
	function ReadToDB($id){
		$query= "UPDATE #__contactus_data SET `read` = 1 where id={$id}";
		$this->_db->setQuery($query);
		$this->_db->execute();	
	}
}
