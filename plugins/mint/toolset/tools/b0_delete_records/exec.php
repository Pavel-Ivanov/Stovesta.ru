<?php
$app = JFactory::getApplication();
$db  = JFactory::getDbo();

//Params:
// item_id - конкретная запись
// section_id - Раздел
// type_id - тип контента
// status - состояние Опубликован / Не опубликован

// Если не выбран тип контента, возврат
if (!$params['section_id']) {
	$app->enqueueMessage(JText::_('Не выбран Раздел'));
	return;
}
// Если не выбран тип контента, возврат
if (!$params['type_id']) {
	$app->enqueueMessage(JText::_('Не выбран Тип контента'));
	return;
}

$itemId = (int)$params['item_id'];
// Раздел
$section_id = stristr($params['section_id'], ':', true);
$section_name = stristr($params['section_id'], ':');

//Тип контента
$type_id = $params->get('type_id', 0);

// Состояние
$status= $params->get('status', '');

$query = $db->getQuery(TRUE);
$query->select('*');
$query->from('#__js_res_record');
if ($itemId !== 0) {
    $query->where("id=$itemId");
}
else {
    $query->where('section_id = ' . $section_id);
    $query->where('type_id = ' . $type_id);
    if ($status) {
        $query->where(($status == 1) ? 'published=1' : 'published=0');
    }
}
$db->setQuery($query);
$items = $db->loadObjectList();

if(!empty($items)) {
	foreach($items as $item) {
		delete_record($item);
	}
	$app->enqueueMessage(JText::_('Все записи удалены'));
}
else {
	$app->enqueueMessage(JText::_('Ничего не найдено'));
}

function delete_record ($record) {
//	$app = JFactory::getApplication();

	$record_id = $record->id;
	//$app->enqueueMessage($record_id);
	$record_type = $record->type_id;

	$type = ItemsStore::getType($record_type);

	if($type->params->get('audit.versioning')) {
		$versions = JTable::getInstance('Audit_versions', 'CobaltTable');
		$version  = $versions->snapshot($record_id, $type);
	}

	$db = JFactory::getDbo();
    executeQuery($db, "DELETE FROM #__js_res_record_category WHERE record_id=$record_id");
//	$db->setQuery("DELETE FROM #__js_res_record_category WHERE record_id = " . $record_id);
//	$db->execute();

    executeQuery($db, "DELETE FROM #__js_res_record_values WHERE record_id=$record_id");
//	$db->setQuery("DELETE FROM #__js_res_record_values WHERE record_id = " . $record_id);
//	$db->execute();

    executeQuery($db, "DELETE FROM #__js_res_tags_history WHERE record_id=$record_id");
//	$db->setQuery("DELETE FROM #__js_res_tags_history WHERE record_id = " . $record_id);
//	$db->execute();

	$db->setQuery("SELECT * FROM #__js_res_files WHERE record_id = " . $record_id);
	$files = $db->loadObjectList('id');

	if(!empty($files) && !$type->params->get('audit.versioning')) {
		$field_table   = JTable::getInstance('Field', 'CobaltTable');
		$cobalt_params = JComponentHelper::getParams('com_cobalt');

		foreach($files AS $file) {
			$field_table->load($file->field_id);
			$field_params = new JRegistry($field_table->params);
			$subfolder    = $field_params->get('params.subfolder', $field_table->field_type);
			if(JFile::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . $cobalt_params->get('general_upload') . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $file->fullpath)) {
				unlink(JPATH_ROOT . DIRECTORY_SEPARATOR . $cobalt_params->get('general_upload') . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $file->fullpath);
			}
			// deleting image field files
			elseif(JFile::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . $file->fullpath)) {
				unlink(JPATH_ROOT . DIRECTORY_SEPARATOR . $file->fullpath);
			}
		}
		$db->setQuery("DELETE FROM #__js_res_files WHERE id IN (" . implode(',', array_keys($files)) . ")");
		$db->execute();
	}

	if($files) {
		$db->setQuery("UPDATE #__js_res_files SET `saved` = 2 WHERE id IN (" . implode(',', array_keys($files)) . ")");
		$db->execute();
	}

	if($type->params->get('audit.versioning')) {
		$restore['files']     = json_encode($files);
		$restore['record_id'] = $record_id;
		$restore['dtime']     = JFactory::getDate()->toSql();

		$db->setQuery("SELECT * FROM #__js_res_comments WHERE record_id = " . $record_id);
		$restore['comments'] = json_encode($db->loadAssocList());

		$db->setQuery("SELECT * FROM #__js_res_favorite WHERE record_id = " . $record_id);
		$restore['favorites'] = json_encode($db->loadAssocList());

		$db->setQuery("SELECT * FROM #__js_res_hits WHERE record_id = " . $record_id);
		$restore['hits'] = json_encode($db->loadAssocList());

		$db->setQuery("SELECT * FROM #__js_res_subscribe WHERE type = 'record' AND ref_id = " . $record_id);
		$restore['subscriptions'] = json_encode($db->loadAssocList());

		$db->setQuery("SELECT * FROM #__js_res_vote WHERE (ref_id = " . $record_id .
			" AND ref_type = 'record') OR (ref_id IN(SELECT id FROM #__js_res_comments WHERE record_id = " . $record_id . ") AND ref_type = 'comment')");
		$restore['votes'] = json_encode($db->loadAssocList());

		$db->setQuery("SELECT * FROM #__js_res_notifications WHERE ref_1 = " . $record_id);
		$restore['notifications'] = json_encode($db->loadAssocList());

		$restore['type_id'] = $type->id;

		$table = JTable::getInstance('Audit_restore', 'CobaltTable');
		$table->save($restore);
	}

	$db->setQuery("DELETE FROM #__js_res_vote WHERE (ref_id = " . $record_id .
		" AND ref_type = 'record') OR (ref_id IN(SELECT id FROM #__js_res_comments WHERE record_id = " . $record_id . ") AND ref_type = 'comment')");
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_comments WHERE record_id = " . $record_id);
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_favorite WHERE record_id = " . $record_id);
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_hits WHERE record_id = " . $record_id);
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_subscribe WHERE type = 'record' AND ref_id = " . $record_id);
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_notifications WHERE ref_1 = " . $record_id);
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_record WHERE parent = 'com_cobalt' AND parent_id = " . $record_id);
	$db->execute();

	$db->setQuery("DELETE FROM #__js_res_record WHERE parent = 'com_cobalt' AND id = " . $record_id);
	$db->execute();

	ATlog::log($record, ATlog::REC_DELETE);

	JPluginHelper::importPlugin('mint');
	$dispatcher = JEventDispatcher::getInstance();
	$dispatcher->trigger('onRecordDelete', array($record));

	return TRUE;
}

function executeQuery($db, $sql) {
    $db->setQuery($sql);
    $db->execute();
}