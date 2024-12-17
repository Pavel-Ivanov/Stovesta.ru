<?php
defined('_JEXEC') or die();

/**
 * Class LscartModelOrder
 */
class LscartModelOrder extends JModelAdmin
{
	/**
	 * Abstract method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   12.2
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm($this->option.'oder', 'order', array('control'=>'jform', 'load_data'=>$loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * @param string $name
	 * @param string $prefix
	 * @param array  $options
	 *
	 * @return JTable
	 */
	public function getTable($name = 'Order', $prefix = 'LscartTable', $options = array())
	{
		return JTable::getInstance($name, $prefix, $options);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 *
	 * @since   12.2
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_lscart.edit.order.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
}