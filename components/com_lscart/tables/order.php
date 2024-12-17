<?php
defined('_JEXEC') or die();

/**
 * Class LscartTableOrder
 */
class TableOrder extends JTable
{
	public function __construct($db)
	{
		parent::__construct('#__lscart_orders', 'id', $db);
	}
}