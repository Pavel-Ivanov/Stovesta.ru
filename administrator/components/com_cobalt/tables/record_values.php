<?php
defined('_JEXEC') or die();
jimport('joomla.table.table');

class CobaltTableRecord_values extends JTable
{
	public function __construct(&$_db)
	{
		parent::__construct('#__js_res_record_values', 'id', $_db);
	}

	public function bind($array, $ignore = ''): bool
    {
//		$params = JRequest::getVar('params', array(), 'post', 'array');
		$params = JFactory::getApplication()->input->get('params', [], 'array');
		if($params) {
			$registry = new JRegistry();
			$registry->loadArray($params);
			$array['params'] = (string)$registry;
		}
		return parent::bind($array, $ignore);
	}

	public function check(): bool
    {
		if(trim($this->field_label) === '') {
            throw new RuntimeException(JText::_('CNOLABEL'), 500);
//			$this->setError(JText::_('CNOLABEL'));
//			return FALSE;
		}

		if(!$this->ip) {
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}

		if($this->ctime === '' || $this->ctime === '0000-00-00 00:00:00') {
			$this->ctime = JFactory::getDate()->toSql();
		}
		return TRUE;
	}

	public function clean($record_id, $ids): bool
    {
		$query = $this->_db->getQuery(TRUE);
		$query->delete();
		$query->from($this->_tbl);
		$query->where('record_id = ' . (int)$record_id);
		if($ids) {
			$query->where('field_id IN (' . implode(',', $ids) . ')');
		}
		$this->_db->setQuery($query);
		$this->_db->execute();

		return TRUE;
	}

	public function store_value($value, $key, $item, $field): void
    {
		$save = array(
			'record_id'   => $item->id,
			'user_id'     => $item->user_id,
			'type_id'     => $item->type_id,
			'section_id'  => $item->section_id,
			'category_id' => 0,
			'params'      => '',
			'ip'          => $_SERVER['REMOTE_ADDR'],
			'ctime'       => JFactory::getDate()->toSql(),
			'field_type'  => $field->type,
			'field_label' => $field->label_orig,
			'field_key'   => 'k' . md5($field->label_orig . '-' . $field->type),
			'value_index' => $key,
			'field_id'    => $field->id
		);

		if(is_array($value) || is_object($value)) {
			$value = json_encode($value, JSON_THROW_ON_ERROR);
		}
		$save['field_value'] = trim(CensorHelper::cleanText($value));

		if(!$save['field_value']) {
			return;
		}

		$this->bind($save);
		$this->store();
	}
}
