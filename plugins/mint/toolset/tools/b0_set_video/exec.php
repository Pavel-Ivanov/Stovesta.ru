<?php
require_once JPATH_ROOT . '/components/com_cobalt/api.php';
JImport('b0.Work.WorkIds');
JImport('b0.fixtures');

$app = JFactory::getApplication();

// Выбранный раздел
if (!$params['section_id']) {
	$app->enqueueMessage(JText::_('Не выбран Раздел'));
	return;
}
$section_id = stristr($params['section_id'], ':', true);

// Выбранный тип контента

if (!$params['type_id']) {
	$app->enqueueMessage(JText::_('Не выбран Тип контента'));
	return;
}
$type_id = $params['type_id'];
//$app->enqueueMessage($type_id);

//Сколько выбирать записей
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

// ID полей Видео
$idVideoOld = $params['id_video_old'];
$idVideoNew = $params['id_video_new'];

$isUpdate = $params['is_update'];

$file_path = JPATH_ROOT . "/logs/b0_set_video_log.txt";
$file_handle = fopen($file_path, 'ab+');
if ($file_handle == false) {
	$app->enqueueMessage('B0 add value - Ошибка открытия файла b0_set_body_log.txt');
	return;
}
$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");

if ($res == false) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл b0_set_body_log.txt');
	fclose($file_handle);
	return;
}

//fwrite($file_handle, 'Устанавливаем: '. $value_to_add . "\n");

$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record WHERE section_id={$section_id} AND type_id={$type_id}";

if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи для перезаписи' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('B0 set Body - Не найдены записи для установки');
	return;
}

foreach($items as $item) {
	$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
	
	$str = $item->id . ' : ' . $item->title;
	if (isset($fields[$idVideoOld]['link']['0'])) {
		$link = substr(strrchr($fields[$idVideoOld]['link'][0], "/"), 1);
		$fields[$idVideoNew] = $link;
		
		if ($isUpdate === '1') {
			if (!CobaltApi::updateRecord((int) $item->id, [], $fields)) {
				fwrite($file_handle,'Ошибка обновления записи' . "\n");
				continue;
			}
			$str .= ' / установлено';
		}
		else {
			$str .= ' / тест';
		}
		fwrite($file_handle, $str . "\n");
	}
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('B0 set Body - Completed successfully');

function writeLog($file_handle, $string)
{
	fwrite($file_handle, $string . " \n");
}

