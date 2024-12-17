<?php
$app = JFactory::getApplication();
$db  = JFactory::getDbo();

$section_id = stristr($params['section_id'], ':', true);
$section_name = stristr($params['section_id'], ':');

$conditions = ['section_id='.$section_id];

try
{
	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_hits'));
	//$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();

	$app->enqueueMessage(JText::_('All items deleted'));
}
catch(Exception $e)
{
	return FALSE;
}
