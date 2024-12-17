<?php
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

$app = JFactory::getApplication();
/**
 * @var array $params
 */

// Проверяем введенные параметры
// Выбранный раздел
if (!$params['section_id']) {
	$app->enqueueMessage('Не выбран Раздел');
	return;
}
$section_id = stristr($params['section_id'], ':', true);

//Поле, в котором меняем значение $value_to_search
if (!$params['field_id']) {
	$app->enqueueMessage('Не выбрано поле, в котором заменяем');
	return;
}
$field_id =$params['field_id'];

//Значение, которое заменяем
if (!$params['value']) {
	$app->enqueueMessage('Не выбрано значение, которое меняем');
	return;
}
$value = $params['value'];

//Значение, на которое заменяем
if (!$params['value_to_change']) {
	$app->enqueueMessage('Не выбрано значение, на которое меняем');
	return;
}
$value_to_change   = $params['value_to_change'];

//Точный или не точный поиск
$is_exact_search = $params['search_type'];

//Сколько выбирать записей в формате
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

$isUpdate = $params['is_update'];

// Проверяем запись в файл лога
$file_path = JPATH_ROOT . "/logs/b0_change_value_log.txt";
$file_handle = fopen($file_path, 'ab+');
if ($file_handle === false) {
	$app->enqueueMessage('B0 change value - Ошибка открытия файла');
	return;
}

$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if ($res === false) {
	$app->enqueueMessage('B0 change value - Ошибка записи в файл');
	fclose($file_handle);
	return;
}

$res = fwrite($file_handle, 'Меняем: '. $value .' на: '.$value_to_change . "\n");

// Получаем записи из БД
$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record WHERE section_id={$section_id} AND id IN(SELECT record_id FROM #__js_res_record_values WHERE field_id={$field_id}";
if ($is_exact_search) {
	$query .= " AND field_value='{$value}')";
}
else {
	$query .= " AND field_value LIKE '%{$value}%')";
}

if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Пустой запрос, нечего менять' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 change value - Пустой запрос, нечего менять');
	return;
}

foreach($items as $item) {
	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);

	$str = $item->id . ' : ' . $item->title;
	
	if (in_array($value_to_change, $fields[$field_id])) {
		// новое уже есть, просто удаляем старое
		unset($fields[$field_id][array_search($value, $fields[$field_id], true)]);
	}
	else {
		// нового значения нет, удаляем старое и добавляем новое
		unset($fields[$field_id][array_search($value, $fields[$field_id], true)]);
		$fields[$field_id][] = $value_to_change;
	}
	
	
	foreach ($fields[$field_id] as $key => $fieldValue) {
		if ($fieldValue == $value) {
			$fields[$field_id][$key] = $value_to_change;
		}
	}
	
	if ($isUpdate === '1') {
		if (!CobaltApi::updateRecord((int) $item->id, [], $fields)) {
			fwrite($file_handle,'Ошибка обновления записи' . "\n");
			continue;
		}
		$str .= ' / заменено';
	}
	else {
		$str .= ' / тест';
	}
	
	fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 change value - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}
