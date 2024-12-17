<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class TableContactus extends JTable
{

	var $id = null;
	var $created_at = null;
  function __construct(&$db)
  {
    parent::__construct('#__contactus_data', 'id', $db);
  }
}
?>