<?php
//require_once JPATH_ROOT . '/components/com_cobalt/api.php';
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.fixtures');

$app = JFactory::getApplication();

// Выбранный раздел
/*if (!$params['section_id']) {
	$app->enqueueMessage(JText::_('Не выбран Раздел'));
	return;
}*/

$section_id = strstr($params['section_id'], ':', true);

// Выбранный тип контента
/*if (!$params['type_id']) {
	$app->enqueueMessage(JText::_('Не выбран Тип контента'));
	return;
}
*/
//$type_id = $params['type_id'];
//$app->enqueueMessage($type_id);

//Сколько выбирать записей
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

$file_path = JPATH_ROOT . '/logs/b0_export_filters_to_csv.csv';
$file_handle = fopen($file_path, 'wb+');
if ($file_handle == false) {
	$app->enqueueMessage('B0 add value - Ошибка открытия файла b0_export_filters_to_csv_log.txt');
	return;
}

$db  = JFactory::getDbo();
$query = "SELECT id, section_id, title, fields FROM #__js_res_record WHERE section_id IN (1, 6) AND published=1 ORDER BY id";
if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
$db->setQuery($query);
$items = $db->loadAssocList();
//b0dd($items);
//b0dd(json_decode($items[0]['fields'], TRUE));

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи для добавления' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 add value - Не найдены записи для добавления');
	return;
}

foreach($items as $item)
{
	$result = [];
	$section_id = $item['section_id'];
	$fields = json_decode($item['fields'], true, 512, JSON_THROW_ON_ERROR);

//	$result[] = $item['id'];
	$result[] = $section_id == AccessoryIds::ID_SECTION ? $fields[AccessoryIds::ID_PRODUCT_CODE] : $fields[SparepartIds::ID_PRODUCT_CODE];
	if ($section_id == AccessoryIds::ID_SECTION) {
		$result[] = is_array($fields[AccessoryIds::ID_MODEL]) ? implode(',', $fields[AccessoryIds::ID_MODEL]) : '';
//		$result[] = is_array($fields[AccessoryIds::ID_YEAR]) ? implode(',', $fields[AccessoryIds::ID_YEAR]) : '';
		$result[] = is_array($fields[AccessoryIds::ID_MOTOR]) ? implode(',', $fields[AccessoryIds::ID_MOTOR]) : '';
		$result[] = is_array($fields[AccessoryIds::ID_DRIVE]) ? implode(',', $fields[AccessoryIds::ID_DRIVE]) : '';
//		$result[] = is_array($fields[AccessoryIds::ID_GENERATION]) ? implode(',', $fields[AccessoryIds::ID_GENERATION]) : '';
	}
	else {
		$result[] = is_array($fields[SparepartIds::ID_MODEL]) ? implode(',', $fields[SparepartIds::ID_MODEL]) : '';
//		$result[] = is_array($fields[SparepartIds::ID_YEAR]) ? implode(',', $fields[SparepartIds::ID_YEAR]) : '';
		$result[] = is_array($fields[SparepartIds::ID_MOTOR]) ? implode(',', $fields[SparepartIds::ID_MOTOR]) : '';
		$result[] = is_array($fields[SparepartIds::ID_DRIVE]) ? implode(',', $fields[SparepartIds::ID_DRIVE]) : '';
//		$result[] = is_array($fields[SparepartIds::ID_GENERATION]) ? implode(',', $fields[SparepartIds::ID_GENERATION]) : '';
	}
	
	fputcsv($file_handle, $result);
}

fclose($file_handle);
$app->enqueueMessage('B0 add value - Completed successfully');

function array_unshift_assoc(&$arr, $key, $val)
{
	$arr = array_reverse($arr, true);
	$arr[$key] = $val;
	$arr = array_reverse($arr, true);
	return $arr;
}

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}

