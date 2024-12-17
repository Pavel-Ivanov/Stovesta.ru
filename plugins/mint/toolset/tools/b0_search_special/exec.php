<?php
JImport('b0.fixtures');
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

$app = JFactory::getApplication();


//Сколько выбирать записей в формате
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];
// Проверяем запись в файл лога
$file_path = JPATH_ROOT . '/logs/b0_search_special.txt';
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
// Получаем записи из БД
$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record
	WHERE id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (47,82) AND field_value=1))";
	$query .= " AND id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (9,83) AND field_value=''))";

$query .= ' ORDER BY title ';
if ($limit_number > 0) {
	$query .= " LIMIT $limit_start, $limit_number";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();
//b0dd($items);
if (empty($items)) {
	fwrite($file_handle, 'Пустой запрос, нечего менять' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 add value by condition - Пустой запрос, нечего менять');
	return;
}

foreach($items as $item)
{
//	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);

	$str = $item->id . ' : ' . $item->title;

	fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 add value by condition - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}
