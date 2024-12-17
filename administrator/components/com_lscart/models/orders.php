<?php
defined('_JEXEC') or die();

/**
 * Class LscartModelOrders
 */
class LscartModelOrders extends JModelList
{
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   12.2
	 */
	protected function getListQuery()
	{
		$query = parent::getListQuery();
		$query->select('*');
		$query->from('#__lscart_orders');
		return $query;
	}
}