<?php

defined('_JEXEC') or die();

require_once JPATH_SITE . '/components/com_content/router.php';

class plgSearchB0 extends JPlugin
{

	function onContentSearchAreas()
	{
		
		static $areas = null;
		
		if($areas == null)
		{
			$sql = "SELECT * FROM `#__js_res_sections` WHERE published = 1 ";

			if($this->params->get('show_restricted', 1) == 0)
			{
				$sql .= ' AND access IN('.implode(',', JFactory::getUser()->getAuthorisedViewLevels()).')';
			}

			$sections = (array)$this->params->get('sections', array());
			if(!empty($sections))
			{
				$sql .= " AND id NOT IN(" . implode(',', $sections) . ")";
			}
			$db = JFactory::getDbo();
			$db->setQuery($sql);
			$sec = $db->loadObjectList('id');
			
			foreach($sec as $section)
			{
				$areas[$section->id . '_section'] = $section->name;
			}
		}
		
		return $areas;
	}

	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$out = array();
		$text = trim($text);
		
		if($text == '') {
			return $out;
		}
		
		$db = JFactory::getDbo();

        $search = $db->quote($db->escape($text.'*'));
        $intersect = array_keys($this->onContentSearchAreas());
        if(is_array($areas))
        {
            $intersect = array_intersect($areas, array_keys($this->onContentSearchAreas()));
        }

        if(! $intersect)
        {
            return $out;
        }
        JArrayHelper::toInteger($intersect);

        $limit = $this->params->def('search_limit', 50);

		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__js_res_record');
		$query->where('published = 1');
		$query->where('section_id IN (' . implode(',', $intersect) . ')');
        $query->where("MATCH (fieldsdata) AGAINST ({$search} IN BOOLEAN MODE)");
		$query->order('title ASC');

		$db->setQuery($query, 0, $limit);
		$result = $db->loadObjectList();
		settype($result, 'array');
		
		$out = array();
		
		foreach($result as $key => $record)	{
			$out[$key] = new stdClass();
			$out[$key]->title = $record->title;
			$out[$key]->text = $record->fieldsdata;
			$out[$key]->created = $record->ctime;
			$out[$key]->href = Url::record($record);
			$areas = $this->onContentSearchAreas();
			$out[$key]->section = $areas[$record->section_id . '_section'];
			$out[$key]->browsernav = 0;
		}
		return $out;
	}
}
