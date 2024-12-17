<?php
defined('_JEXEC') or die;
class ModContactusHelper
{
	public static function getFields($id)
	{
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if (isset($id) && ($id > 0)) {
            $query->select('params')
                ->from('#__modules')
                ->where("id=$id");
        } else {
            $query->select('params')
                ->from('#__modules')
                ->where('module="mod_contactus"');
        }
        $db->setQuery($query);
        $array =  $db->loadAssoc();
        $fields =  json_decode($array['params']);
			return $fields;
	}
}
