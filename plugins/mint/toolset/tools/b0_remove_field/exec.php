<?php
require_once JPATH_ROOT . '/components/com_cobalt/api.php';
JImport('b0.fixtures');

$app = JFactory::getApplication();

// Выбранный раздел
if (!$params['section_id']) {
	$app->enqueueMessage('Не выбран Раздел');
	return;
}
$section_id = strstr($params['section_id'], ':', true);

// Выбранный тип контента
if (!$params['type_id']) {
	$app->enqueueMessage('Не выбран Тип контента');
	return;
}
$type_id = $params['type_id'];

if (!$params['field_id']) {
	$app->enqueueMessage('Не выбрано поле, которое удаляем');
	return;
}
$field_id = $params['field_id'];
$itemId = (int)$params['item_id'];
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];
$isUpdate = $params['is_update'];


$file_path = JPATH_ROOT . "/logs/b0_remove_field_log.txt";
$file_handle = fopen($file_path, 'ab+');
if ($file_handle === false) {
	$app->enqueueMessage('Ошибка открытия файла b0_remove_field_log.txt');
	return;
}
$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");

if ($res === false) {
	$app->enqueueMessage('Ошибка записи в файл b0_remove_field_log.txt');
	fclose($file_handle);
	return;
}


fwrite($file_handle, 'Удаляем поле '. $field_id . "\n");

$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record";

if ($itemId !== 0) {
    $query.= " WHERE id=$itemId";
}
else {
    $query.= " WHERE section_id=$section_id AND type_id=$type_id AND published=1";
    if ($limit_number > 0) {
        $query.= " LIMIT $limit_start, $limit_number";
    }
}

fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('Не найдены записи');
	return;
}
$app->enqueueMessage('Всего записей: '. count($items));
$processedItems = 0;
foreach($items as $item) {
	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
	$logStr = $item->id . ' : ' . $item->title;

	if (!isset($fields[$field_id])) {
		$logStr .= ' / отсутствует $fields[$field_id], пропускаем';
		fwrite($file_handle, $logStr . "\n");
		continue;
	}
//    b0debug($fields[$field_id]);
	unset($fields[$field_id]);
//	b0dd($fields[$field_id]);
    if ($isUpdate === '1') {
        //Пример вызова - CobaltApi::updateRecord($item_id, $data, $fields, $categories, $tags)
        if (!CobaltApi::updateRecord((int)$item->id, [], $fields)) {
            fwrite($file_handle, 'Ошибка обновления записи' . "\n");
            continue;
        }
        $logStr .= ' / удалено';
    }
    else {
        $logStr .= ' / тест';
    }
	fwrite($file_handle, $logStr . "\n");
    $processedItems++;
}

fwrite($file_handle, "Успешно завершено: $processedItems\n");
fclose($file_handle);
$app->enqueueMessage("Успешно завершено: $processedItems");
