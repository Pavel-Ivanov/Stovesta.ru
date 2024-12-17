<?php
JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

$app = JFactory::getApplication();

// Проверяем введенные параметры
// Выбранный раздел
if (!$params['section_id']) {
	$app->enqueueMessage('Не выбран Раздел');
	return;
}
$section_id = strstr($params['section_id'], ':', true);

//Поле, в котором ищем контрольные значения $value_to_search_1
if (!$params['field_id_to_search_1']) {
	$app->enqueueMessage('Не выбрано поле, в котором искать');
	return;
}
$field_id_to_search_1 =$params['field_id_to_search_1'];

//Значение, которое ищем
if (!$params['value_to_search_1']) {
	$app->enqueueMessage('Не выбрано значение для поиска');
	return;
}
$value_to_search_1 = $params['value_to_search_1'];

if (!$params['field_id_to_search_2']) {
	$field_id_to_search_2 = 0;
}
else {
	$field_id_to_search_2 =$params['field_id_to_search_2'];
}

if (!$params['value_to_search_2']) {
	$value_to_search_2 = '';
}
else {
	$value_to_search_2 = $params['value_to_search_2'];
}

//Поле, в которое добавляем значение $value_to_add
if (!$params['field_id_to_add']) {
	$app->enqueueMessage('Не выбрано поле, в которое добавляем');
	return;
}
$field_id_to_add   = $params['field_id_to_add'];

//Значение, которое добавляем
if (!$params['value_to_add']) {
	$app->enqueueMessage('Не выбрано поле, в которое добавляем');
	return;
}
$value_to_add   = $params['value_to_add'];

//Точный или не точный поиск
$is_exact_search = $params['search_type'];

//Сколько выбирать записей в формате
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];
// Проверяем запись в файл лога
$file_path = JPATH_ROOT . '/logs/b0_add_value_condition_double_log.txt';
$file_handle = fopen($file_path, 'ab+');
if ($file_handle === false) {
	$app->enqueueMessage('B0 add value by condition - Ошибка открытия файла add_value_condition_double_log.txt');
	return;
}

$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if ($res === false) {
	$app->enqueueMessage('B0 add value by condition - Ошибка записи в файл add_value_condition_log.txt');
	fclose($file_handle);
	return;
}
$log_str = 'Добавляем: '. $value_to_add .' если установлен: '.$value_to_search_1;
if ($params['value_to_search_2']) {
	$log_str .= ' и ' . $value_to_search_2;
}
$log_str .= "\n";
$res = fwrite($file_handle, $log_str);

// Получаем записи из БД
$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record
	WHERE section_id={$section_id}
	AND id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id={$field_id_to_search_1} AND field_value='{$value_to_search_1}'))";
/*if ($is_exact_search) {
	$query .= " AND field_value='{$value_to_search}')";
}
else {
	$query .= " AND field_value LIKE '%{$value_to_search}%')";
}*/
if ($field_id_to_search_2 !== 0) {
	$query .= " AND id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id={$field_id_to_search_2} AND field_value='{$value_to_search_2}'))";
}
$query .= ' ORDER BY title ';
if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Пустой запрос, нечего менять' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 add value by condition - Пустой запрос, нечего менять');
	return;
}

foreach($items as $item)
{
	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);

	$str = $item->id . ' : ' . $item->title;

	if (!isset($fields[$field_id_to_add])) {
		$str .= ' / отсутствует $fields[$field_id_to_add], пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}

	if(empty($fields[$field_id_to_add])) {
		$str .= ' / пустое $fields[$field_id_to_add], пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}

	// предварительно проверяем на наличие добавляемого значения
	if (in_array($value_to_add, $fields[$field_id_to_add], true)) {
		$str .= ' / уже установлено, пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}

	$fields[$field_id_to_add][] = $value_to_add;

	if (!CobaltApi::updateRecord((int) $item->id, [], $fields)) {
		fwrite($file_handle,'Ошибка обновления записи' . "\n");
		continue;
	}

	$str .= ' / установлено';
	fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 add value by condition - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}
