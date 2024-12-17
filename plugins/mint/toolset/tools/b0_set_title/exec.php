<?php
require_once JPATH_ROOT . '/components/com_cobalt/api.php';
JImport('b0.fixtures');

$app = JFactory::getApplication();

// Проверяем введенные параметры
// Выбранный раздел
if (!$params['section_id']) {
	$app->enqueueMessage('Не выбран Раздел');
	return;
}
$section_id = stristr($params['section_id'], ':', true);

//Значение, которое ищем
if (!$params['value_to_search']) {
	$app->enqueueMessage('Не выбрано значение, которое ищем');
	return;
}
$value_to_search = $params['value_to_search'];

//Значение, которое добавляем
if (!$params['value_to_change']) {
//	$app->enqueueMessage('Не выбрано значение, которое добавляем');
//	return;
	$value_to_change = '';
}
else {
	$value_to_change   = $params['value_to_change'];
}

//Точный или не точный поиск
$is_exact_search = $params['search_type'];

//Сколько выбирать записей в формате
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];
// Проверяем запись в файл лога
$file_path = JPATH_ROOT . "/logs/b0_set_title_log.txt";
$file_handle = fopen($file_path, "a+");
if ($file_handle === false) {
	$app->enqueueMessage('B0 set title - Ошибка открытия файла b0_set_title_log.txt');
	return;
}

$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if ($res === false) {
	$app->enqueueMessage('B0 add value by condition - Ошибка записи в файл b0_set_title_log.txt');
	fclose($file_handle);
	return;
}

$res = fwrite($file_handle, 'Меняем: '. $value_to_search .' на: '. $value_to_change . "\n");

// Получаем записи из БД
$db  = JFactory::getDbo();
$query = "SELECT id,title,fieldsdata,meta_descr FROM #__js_res_record WHERE section_id={$section_id} AND title LIKE '%{$value_to_search}%'";
if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();
if (empty($items)) {
	fwrite($file_handle, 'Пустой запрос, нечего менять' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 set title - Пустой запрос, нечего менять');
	return;
}

foreach($items as $item) {
	$str = $item->id . ' : ' . $item->title;

	$newTitle = trim(str_ireplace($value_to_search, $value_to_change, $item->title));
	$newFieldsdata = trim(str_ireplace($value_to_search, $value_to_change, $item->fieldsdata));
	$newMetaDescr = trim(str_ireplace($value_to_search, $value_to_change, $item->meta_descr));
	
	// Записать новые поля
	$data = [
		'title' => $newTitle,
		'fieldsdata' => $newFieldsdata,
		'meta_descr' => $newMetaDescr,
	];
	if (CobaltApi::updateRecord((int) $item->id, $data)) {
		$str .= ' / установлено';
		fwrite($file_handle, $str . "\n");
	}
	else {
		fwrite($file_handle,'Ошибка обновления записи' . "\n");
		continue;
	}
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 set title - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}

