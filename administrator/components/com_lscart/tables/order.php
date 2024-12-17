<?php
defined('_JEXEC') or die();

/**
 * Class LscartTableOrder
 */
class LscartTableOrder extends JTable
{
	/**
	 * LscartTableOrder constructor.
	 *
	 * @param JDatabaseDriver $db
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__lscart_orders', 'id', $db);
	}
}
