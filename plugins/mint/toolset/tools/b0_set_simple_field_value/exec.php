<?php
JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

try {
	$app = JFactory::getApplication();
}
catch (Exception $e) {
	return;
}

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

//Поле, в котором устанавливаем значение $value_to_set
if (!$params['field_id']) {
	$app->enqueueMessage('Не выбрано поле');
	return;
}
$field_id = $params['field_id'];

//Значение, которое устанавливаем
if (!$params['value']) {
	$app->enqueueMessage('Не выбрано значение для устанановки');
	return;
}
$value = $params['value'];

//Сколько выбирать записей
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

$file_path = JPATH_ROOT . '/logs/b0_set_simple_value_log.txt';
$file_handle = fopen($file_path, 'ab+');
if ($file_handle === false) {
	$app->enqueueMessage('B0 set value - Ошибка открытия файла set_simple_value_log.txt');
	return;
}
$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");

if ($res === false) {
	$app->enqueueMessage('B0 set simple value - Ошибка записи в файл set_simple_value_log.txt');
	fclose($file_handle);
	return;
}

fwrite($file_handle, 'Устанавливаем: '. $params['section_id'] . ' / ' . $field_id . ' = ' . $value . "\n");

$db  = JFactory::getDbo();
$query = "SELECT id, fields, title, alias FROM #__js_res_record WHERE section_id={$section_id} AND type_id={$type_id} AND published=1";

if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи для установки' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 set value - Не найдены записи для установки');
	return;
}

foreach($items as $item){
	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
	$str = $item->id . ' : ' . $item->title;
	
	// если существует - меняем, если нет - добавляем
	if (isset($fields[$field_id])) {
		// если значение уже установлено - пропускаем, если нет - меняем
		if ($fields[$field_id] === $value) {
			$str .= ' / уже установлено, пропускаем';
			fwrite($file_handle, $str . "\n");
			continue;
		}
		$fields[$field_id] = $value;
		$str .= ' / меняем';
	}
	else {
		$fields[$field_id][] = $value;
		$str .= ' / добавляем';
	}
	
	//Пример вызова - CobaltApi::updateRecord($item_id, $data, $fields, $categories, $tags)
	try {
		CobaltApi::updateRecord((int) $item->id, [], $fields);
	}
	catch (Exception $e) {
//		fwrite($file_handle, 'Ошибка обновления записи' . "\n");
		fwrite($file_handle, $e . "\n");
		continue;
	}
	
	$str .= ' / установлено';
	fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 set value - Completed successfully');
