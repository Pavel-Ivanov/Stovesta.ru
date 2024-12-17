<?php
defined('_JEXEC') or die;
JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/library/php/fields/cobaltrelate.php';
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

class JFormFieldCB0parent extends CFormFieldRelate
{
	public function getInput()
	{
		// $this->request->get('id') - id этой записи
		//$name = "jform[fields][$this->id]";
		ArrayHelper::clean_r($this->value);
		//b0debug($this->request->get('id'));

		//$this->inputvalue = $this->_render_input($this->params->get('params.input_mode'), $name, $this->request->getInt('section_id'), $this->_getTypes());
		// Режим ввода: 5 -  в отдельном окне
		$inputMode = $this->params->get('params.input_mode');
		// ???
		$name = "jform[fields][$this->id]";
		// id Раздела
		$sectionId = $this->params->get('params.child_section');
		// id типа
		$typeId = $this->params->get('params.child_type');
		$this->inputvalue = $this->_render_input($inputMode, $name, $sectionId, $typeId);
		return $this->_display_input();
	}

	public function onRenderFull($record, $type, $section)
	{
		$this->request->set('_rmfid', $this->id);
		$this->request->set('_rmrid', $record->id);
		$this->request->set('_rmstrict', $this->params->get('params.strict'));

		$api           = new CobaltApi();
		$this->content = $api->records(
			$this->params->get('params.child_section'),
			'show_related',
			$this->params->get('params.orderby', 'r.ctime DESC'),
			$this->params->get('params.child_type'),
			NULL,
			$this->params->get('params.cat_id', 0),
			$this->params->get('params.multi_limit', 10),
			$this->params->get('params.tmpl_list', 'default')
		);
		return $this->_display_output('full', $record, $type, $section);
	}

	public function onRenderList($record, $type, $section)
	{
		$this->request->set('_rmfid', $this->id);
		$this->request->set('_rmrid', $record->id);
		$this->request->set('_rmstrict', $this->params->get('params.strict'));
		
		$api           = new CobaltApi();
		$this->content = $api->records(
			$this->params->get('params.child_section'),
			'show_related',
			$this->params->get('params.orderby', 'r.ctime DESC'),
			$this->params->get('params.child_type'),
			NULL,
			$this->params->get('params.cat_id', 0),
			$this->params->get('params.multi_limit', 10),
			$this->params->get('params.tmpl_list', 'default')
		);
		return $this->_display_output('list', $record, $type, $section);
	}

	public function onGetList($params)
	{
		$sectionId = $this->params->get('params.child_section');
		$typeId = $this->params->get('params.child_type');
		
		$db         = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('id, title, null, title');
		$query->from('#__js_res_record');
		$query->where('published = 1');
		$query->where('hidden = 0');
		$query->where('section_id = ' . $sectionId);
		$query->where('type_id = ' . $typeId);
		$db->setQuery($query);
		return $db->loadRowList();
	}

	private function _getTypes()
	{
		$types   = $this->params->get('params.child_type');
		$types[] = JModelLegacy::getInstance('Fields', 'CobaltModel')->getFieldTypeId($this->id);
		ArrayHelper::clean_r($types);
		JArrayHelper::toInteger($types);
		return $types;
	}
}
