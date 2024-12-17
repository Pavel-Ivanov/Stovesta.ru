<?php
defined('_JEXEC') or die();

class LscartModelLscart extends JModelList
{
	public function getListQuery()
	{
		$query = parent::getListQuery();

//		$app = JFactory::getApplication();
//		$cart = $app->getUserState('cart');
		$cart = JFactory::getApplication()->getUserState('cart');
		if (!$cart) {
			return $query;
		}
		$cart_keys = implode(',', array_keys($cart));

		$query->select('id, title, alias, type_id, fields');
		$query->from('#__js_res_record');
		$query->where('id IN ('.$cart_keys.')');
		$query->order('title');
		return $query;
	}
}
