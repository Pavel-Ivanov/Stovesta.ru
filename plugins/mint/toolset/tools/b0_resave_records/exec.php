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
//$app->enqueueMessage($type_id);

//Поле, в которм удаляем значение $value
/*if (!$params['field_id']) {
	$app->enqueueMessage('Не выбрано поле, в котором удаляем');
	return;
}
$field_id = $params['field_id'];*/

//Значение, которое добавляем
/*if (!$params['value']) {
	$app->enqueueMessage('Не выбрано значение, которое удаляем');
	return;
}
$value   = $params['value'];*/

//Сколько выбирать записей
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

$file_path = JPATH_ROOT . "/logs/b0_resave_records_log.txt";
$file_handle = fopen($file_path, 'ab+');
if ($file_handle === false) {
	$app->enqueueMessage('B0 reSave records - Ошибка открытия файла b0_resave_records_log.txt');
	return;
}
$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");

if ($res === false) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл b0_resave_records_log.txt');
	fclose($file_handle);
	return;
}

fwrite($file_handle, 'Удаляем: '. $value . "\n");

$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record WHERE section_id={$section_id} AND type_id={$type_id}";

if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 reSave records - Не найдены записи');
	return;
}

foreach($items as $item)
{
	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
	$categories = json_decode($item->categories, true, 512, JSON_THROW_ON_ERROR);
	$str = $item->id . ' : ' . $item->title;

/*	if (!isset($fields[$field_id])) {
		$str .= ' / отсутствует $fields[$field_id], пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}*/

/*	if(empty($fields[$field_id])) {
		$str .= ' / пустое $fields[$field_id], пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}*/

	// предварительно проверяем на наличие удаляемого значения
/*	if (!in_array($value, $fields[$field_id], true)) {
		$str .= ' / не установлено, пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}*/

	$pos = array_search($value, $fields[$field_id], true);
//	b0dd($pos);
	unset($fields[$field_id][$pos]);
//	b0debug($fields[$field_id]);
	//Пример вызова - CobaltApi::updateRecord($item_id, $data, $fields, $categories, $tags)
/*	if (!CobaltApi::updateRecord((int)$item->id, [], $fields, $categories)) {
		fwrite($file_handle,'Ошибка перезаписи' . "\n");
		continue;
	}*/

	$str .= ' / перезаписано';
	fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 reSave records - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}
