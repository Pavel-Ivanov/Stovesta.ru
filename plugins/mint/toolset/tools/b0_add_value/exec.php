<?php
require_once JPATH_ROOT . '/components/com_cobalt/api.php';

$app = JFactory::getApplication();

// Выбранный раздел
if (!$params['section_id']) {
	$app->enqueueMessage(JText::_('Не выбран Раздел'));
	return;
}
else {
	$section_id = stristr($params['section_id'], ':', true);
}

// Выбранный тип контента

if (!$params['type_id']) {
	$app->enqueueMessage(JText::_('Не выбран Тип контента'));
	return;
}
else {
	$type_id = $params['type_id'];
}
//$app->enqueueMessage($type_id);

//Поле, в которое добавляем значение $value_to_add
if (!$params['field_id_to_add']) {
	$app->enqueueMessage(JText::_('Не выбрано поле, в которое добавляем'));
	return;
}
else {
	$field_id_to_add = $params['field_id_to_add'];
}

//Значение, которое добавляем
if (!$params['value_to_add']) {
	$app->enqueueMessage(JText::_('Не выбрано значение, которое добавляем'));
	return;
}
else {
	$value_to_add   = $params['value_to_add'];
}

//Сколько выбирать записей
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

$file_path = JPATH_ROOT . "/logs/b0_add_value_log.txt";
$file_handle = fopen($file_path, "a+");
if ($file_handle == false) {
	$app->enqueueMessage('B0 add value - Ошибка открытия файла add_value_log.txt');
	return;
}
$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");

if ($res == false) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл add_value_log.txt');
	fclose($file_handle);
	return;
}

fwrite($file_handle, 'Добавляем: '. $value_to_add . "\n");

$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record WHERE section_id={$section_id} AND type_id={$type_id}";

if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи для добавления' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 add value - Не найдены записи для добавления');
	return;
}

foreach($items as $item)
{
	$fields = json_decode($item->fields, TRUE);
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
	if (array_search($value_to_add, $fields[$field_id_to_add])) {
		$str .= ' / уже установлено, пропускаем';
		fwrite($file_handle, $str . "\n");
		continue;
	}

	array_push($fields[$field_id_to_add], $value_to_add);

	//Пример вызова - CobaltApi::updateRecord($item_id, $data, $fields, $categories, $tags)
	if (!CobaltApi::updateRecord($item->id, [], $fields)) {
		fwrite($file_handle,'Ошибка обновления записи' . "\n");
		continue;
	};

	$str .= ' / добавлено';
	fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 add value - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}

