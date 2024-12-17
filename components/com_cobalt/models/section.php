<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');
include_once JPATH_ROOT.'/components/com_cobalt/library/php/helpers/helper.php';

class CobaltModelSection extends JModelAdmin
{
	public function getTable($name = 'Section', $prefix = 'CobaltTable', $options = [])
    {
		return JTable::getInstance($name, $prefix, $options);
	}

	public function getForm($data = [], $loadData = true): bool
    {
		return true;
	}

	public function getItem($id = NULL)
	{
		static $cache = [];
		if(!$id) {
			$id = JFactory::getApplication()->input->getInt('section_id');
		}
		if(isset($cache[$id])) {
			return $cache[$id];
		}

		$section = parent::getItem($id);
		if($section) {
			$section->params = new JRegistry($section->params);

            $section->params->set('events.subscribe_section', 0);

			if(!$section->get('categories', '0')) {
				$section->params->set('events.subscribe_category', 0);
			}

			$descr = JText::_($section->get('description', ''));
			$descr = (!empty($section->get('description', '')) ? JHtml::_('content.prepare', $descr) : '');
			$descr = preg_split('#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i', $descr, 2);
			$section->descr_before = @$descr[0];
			$section->descr_after = @$descr[1];
			$section->descr_full = implode($descr);
			$section->link = Url::records($section);
			$section->name = JText::_($section->name);
		}
		$cache[$id] = $section;

		return $cache[$id];
	}

	public function countUserRecords($section_id, $type_id = null, $byday = false)
	{
		$user = JFactory::getUser();

		$query = $this->_db->getQuery(TRUE);
		$query->select('count(*)');
		$query->from('#__js_res_record');
		$query->where('section_id = '.$section_id);
		if($type_id) {
			$query->where('type_id = '.$type_id);
		}
		if($byday) {
			$start = $this->_db->quote(date('Y-m-d 00:00:00'));
			$end = $this->_db->quote(date('Y-m-d 23:59:59'));
			$query->where("ctime BETWEEN $start AND $end");
		}
		$query->where('user_id = '.$user->get('id'));
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}
	public function getSectionTypes($id)
	{
		$section = $this->getItem($id);

		$ids = implode(',', $section->params->get('general.type'));

		if(!$ids) {
			return false;
		}

		$query = $this->_db->getQuery(true);
		$query->select('id, name, description, params');
		$query->from('#__js_res_types');
		$query->where('published = 1');
		$query->where("id IN($ids)");
		$this->_db->setQuery($query);
		$types = $this->_db->loadObjectList();

		if(!$types) {
			return false;
		}
		return $types;
	}
}
