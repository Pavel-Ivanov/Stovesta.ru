<?php
$app = JFactory::getApplication();
$db  = JFactory::getDbo();

$section_id = stristr($params['section_id'], ':', true);
$section_name = stristr($params['section_id'], ':');

$conditions = ['section_id='.$section_id];

try
{
	if ($params['delete_categories'] == 1) {
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__js_res_categories'));
		$query->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
		unset($query);
	}

	if ($params['delete_section'] == 1) {
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__js_res_sections'));
		$query->where('id='.$section_id);
		$db->setQuery($query);
		$result = $db->execute();
		unset($query);
	}

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_audit_log'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);


	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_category_user'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_comments'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_favorite'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_files'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_hits'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_import'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_moderators'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_packs_sections'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_record'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_record_category'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_record_values'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_sales'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_subscribe'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_subscribe_cat'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_subscribe_user'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_tags_history'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_user_options'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_user_options_autofollow'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_user_options_post_map'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$query = $db->getQuery(true);
	$query->delete($db->quoteName('#__js_res_vote'));
	$query->where($conditions);
	$db->setQuery($query);
	$result = $db->execute();
	unset($query);

	$app->enqueueMessage(JText::_('All records deleted'));
}
catch(Exception $e)
{
	return FALSE;
}
