<?php
JImport('b0.fixtures');
const FIELD_ID_LIST = '(177,194)';
const CSV_FILE_PATH = '/logs/b0_export_images_market_to_csv.csv';
const ERROR_FILE_OPEN_MESSAGE = 'Ошибка открытия файла b0_export_images_market_to_csv.csv';
const SUCCESS_MESSAGE = 'Завершено успешно';

$app = JFactory::getApplication();

$file_handle = openFile(CSV_FILE_PATH);
if (!$file_handle) {
	$app->enqueueMessage(ERROR_FILE_OPEN_MESSAGE);
	return;
}

$db = JFactory::getDbo();
$images = fetchImagesInformationFromDatabase($db);

foreach ($images as $image) {
    $record = fetchRecordFromDatabase($db, $image->record_id);

    $result = prepareResultArray($record, $image);

    fputcsv($file_handle, $result);
}

fclose($file_handle);
$app->enqueueMessage(SUCCESS_MESSAGE);

function openFile($filePath){
    return fopen(JPATH_ROOT . $filePath, 'wb+');
}

function fetchImagesInformationFromDatabase($db){
    $query = $db->getQuery(true);
    $query->select('filename, section_id, record_id, type_id, field_id, fullpath');
    $query->from('#__js_res_files');
    $query->where('field_id IN ' . FIELD_ID_LIST);
    $db->setQuery($query);
    return $db->loadObjectList();
}

function fetchRecordFromDatabase($db, $recordId){
    $query = $db->getQuery(true);
    $query->select('id, title');
    $query->from('#__js_res_record');
    $query->where('id = '. $recordId);
    $db->setQuery($query);
    return $db->loadObject();
}

function prepareResultArray($record, $image): array
{
    $result = [];
    $result[] = $image->record_id;
    $result[] = $record->id ? $record->title : 'Не найдена';
    $result[] = $image->filename;
    $result[] = $image->section_id;
    $result[] = $image->type_id;
    $result[] = $image->field_id;
    $result[] = $image->fullpath;
    return $result;
}
