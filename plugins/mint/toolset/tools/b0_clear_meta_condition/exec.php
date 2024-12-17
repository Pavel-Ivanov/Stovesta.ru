<?php
require_once JPATH_ROOT . '/components/com_cobalt/api.php';
//JImport('b0.Sparepart.SparepartIds');
//JImport('b0.Accessory.AccessoryIds');
//JImport('b0.Work.WorkIds');
JImport('b0.fixtures');

$app = JFactory::getApplication();

/** @var object $params */
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

//Сколько выбирать записей
$limit_start = $params['search_limit_start'];
$limit_number = $params['search_limit_number'];

$isUpdate = $params['is_update'];

$file_path = JPATH_ROOT . "/logs/b0_clear_meta_condition_log.txt";
$file_handle = fopen($file_path, 'ab+');
if (!$file_handle) {
	$app->enqueueMessage('B0 clear meta - Ошибка открытия файла b0_clear_meta_condition_log.txt');
	return;
}
$res = fwrite($file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");

if (!$res) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл b0_clear_meta_condition_log.txt');
	fclose($file_handle);
	return;
}

$db  = JFactory::getDbo();
$query = "SELECT * FROM #__js_res_record WHERE section_id={$section_id} AND type_id={$type_id}";

if ($limit_number > 0) {
	$query .= " LIMIT {$limit_start},{$limit_number}";
}
fwrite($file_handle, $query . "\n");

$db->setQuery($query);
$items = $db->loadObjectList();

if (empty($items)) {
	fwrite($file_handle, 'Не найдены записи для очистки' . "\n");
	fclose($file_handle);
	$app->enqueueMessage('Не найдены записи для очистки');
	return;
}

foreach($items as $item) {
	$str = $item->id . ' : ' . $item->title;
    if ($item->meta_key === '') {
        continue;
    }
    if (!strpos($item->meta_key, ',')) {
        continue;
    }
    if (preg_match('/[A-Z]/', $item->meta_key)) {
        continue;
    }
    if ($isUpdate === '1') {
        $query="UPDATE #__js_res_record SET meta_key='' WHERE id={$item->id}";
        $db->setQuery($query);
        $result = $db->execute();
        if (!$db->execute()) {
            $str .= ' / Ошибка обновления записи';
            fwrite($file_handle,$str . "\n");
            continue;
        }
        $str .= '- '. $item->meta_key . ' / выполнено';
    }
    else {
        $str .= '- '. $item->meta_key . ' / тест';
    }

    fwrite($file_handle, $str . "\n");
}

fwrite($file_handle, 'Completed successfully' . "\n");
fclose($file_handle);
$app->enqueueMessage('Completed successfully');

