<?php
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.fixtures');
include_once JPATH_ROOT.'/components/com_cobalt/api.php';

/**
 * @var array $params
 */

$app = JFactory::getApplication();

$sourceFileName = JPATH_ROOT . '/uploads/'.$params->get('files');
$sourceFileHandle = fopen($sourceFileName, 'rb');
if (!$sourceFileHandle) {
	$app->enqueueMessage('Ошибка открытия файла ' . $sourceFileName, 'error');
	return;
}

$logFilePath = JPATH_ROOT . "/logs/b0_largus_related_log.txt";
$logFileHandle = fopen($logFilePath, 'ab+');
if (!$logFileHandle) {
	$app->enqueueMessage('B0 Largus redirect - Ошибка открытия файла b0_largus_related_log.txt');
	return;
}

$logResult = fwrite($logFileHandle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if (!$logResult) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл b0_largus_related_log.txt');
	fclose($logFileHandle);
	return;
}

$sectionId = $params->get('section_id', 0);
$typeId = $params->get('type_id', 0);
$isUpdate = $params['is_update'];

while (($data_string = fgetcsv($sourceFileHandle, 10000, ",")) !== FALSE) {
	if ($data_string[0] === '#') {
		continue;
	}
	$dataArray = [
		'sourceCode' => $data_string[0],
		'sourceAnalogs' => $data_string[1],
		'sourceAssociated' => $data_string[2],
		'sourceWorks' => $data_string[3],
	];
//	b0debug($dataArray['sourceCode']);
	$logStr = $dataArray['sourceCode'] . ' : ';
	
	if (!$dataArray['sourceAnalogs'] || !$dataArray['sourceAssociated'] || !$dataArray['sourceWorks']) {
		$logStr .= '!!! ошибка в исходной строке, пропускаем';
		fwrite($logFileHandle,$logStr . "\n");
		continue;
	}
	
	if ($dataArray['sourceAnalogs'] === ' ' && $dataArray['sourceAssociated'] === ' ' && $dataArray['sourceWorks'] === ' ') {
		$logStr .= 'нет related, пропускаем';
		fwrite($logFileHandle,$logStr . "\n");
		continue;
	}
//	$logStr = $dataArray['id'] . ' : ' . $dataArray['title'];
	// по sourceCode получаем id товара. если не находим- запись в лог и пропускаем
	$recordId = getIdsByCodes($typeId, $dataArray['sourceCode'])[0];
	if ($recordId) {
		// найдена
		$record= ItemsStore::getRecord((int)$recordId);
		$sourceFields = json_decode($record->fields, true, 512, JSON_THROW_ON_ERROR);
	}
	else {
		$logStr = $dataArray['sourceCode'] . ' : не найдена, пропускаем';
		fwrite($logFileHandle,$logStr . "\n");
		continue;
	}
	
	if ($dataArray['sourceAnalogs'] !== ' ') {
		$analogs = getIdsByCodes($typeId, $dataArray['sourceAnalogs']);
	}
	else {
		$analogs = [];
	}

	if ($dataArray['sourceAssociated'] !== ' ') {
		$associated = getIdsByCodes($typeId, $dataArray['sourceAssociated']);
	}
	else {
		$associated = [];
	}
	
	if ($dataArray['sourceWorks'] !== ' ') {
		$works = getIdsByCodes($typeId, $dataArray['sourceWorks']);
	}
	else {
		$works = [];
	}
	
	setFields($typeId, $sourceFields, $analogs, $associated, $works);
	$logStr .= '('.implode(',', $analogs ).') ('.implode(',', $associated).') (' . implode(',', $works).')';

	if ($isUpdate === '1') {
		if (!CobaltApi::updateRecord((int)$recordId, [], $sourceFields)) {
			$logStr .= ' / ошибка записи';
			fwrite($logFileHandle,$logStr . "\n");
			continue;
		}
		$logStr .= ' - добавлено';
	}
	else {
		$logStr .= ' - тест';
	}
	
	fwrite($logFileHandle, $logStr . "\n");
	
}
fwrite($logFileHandle, 'Завершено успешно' . "\n");
fclose($sourceFileHandle);
fclose($logFileHandle);
$app->enqueueMessage('Загружено успешно', 'notice');

// функция берет массив кодов товара и возвращает массив идентификаторов
function getIdsByCodes (string $typeId, string $codes) :array
{
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			$fieldId = AccessoryIds::ID_PRODUCT_CODE;
			break;
		case SparepartIds::ID_TYPE:
			$fieldId = SparepartIds::ID_PRODUCT_CODE;
			break;
		default:
			$fieldId = '';
			break;
	}
	
	$db  = JFactory::getDbo();
	$query = "SELECT record_id FROM #__js_res_record_values WHERE field_id={$fieldId} AND field_value IN ({$codes})";
//	b0debug($query);
	$db->setQuery($query);
	$idsList = $db->loadObjectList();
	$ids = [];
	foreach ($idsList as $item) {
		$ids[] = $item->record_id;
	}
//	b0dd($ids);
	return $ids;
}

function setFields (string $typeId, array &$fields, array $analogs, array $associated, array $works)
{
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			$fields[AccessoryIds::ID_ANALOGS] = $analogs;
			$fields[AccessoryIds::ID_ASSOCIATED] = $associated;
			$fields[AccessoryIds::ID_WORKS] = $works;
			break;
		case SparepartIds::ID_TYPE:
			$fields[SparepartIds::ID_ANALOGS] = $analogs;
			$fields[SparepartIds::ID_ASSOCIATED] = $associated;
			$fields[SparepartIds::ID_WORKS] = $works;
			break;
	}
//	return $fields;
}
