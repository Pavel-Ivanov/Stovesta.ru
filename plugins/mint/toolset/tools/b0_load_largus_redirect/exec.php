<?php
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Work.WorkIds');
//JImport('b0.Largus.LargusSparepartIds');
//JImport('b0.Largus.LargusAccessoryIds');
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

$targetFilePath = JPATH_ROOT . "/uploads/b0_largus_redirect.txt";
$targetFileHandle = fopen($targetFilePath, 'ab+');
if (!$targetFileHandle) {
	$app->enqueueMessage('B0 Largus redirect- Ошибка открытия файла b0_largus_redirect.txt');
	return;
}

$logFilePath = JPATH_ROOT . "/logs/b0_largus_redirect_log.txt";
$logFileHandle = fopen($logFilePath, 'ab+');
if (!$logFileHandle) {
	$app->enqueueMessage('B0 Largus redirect - Ошибка открытия файла b0_largus_redirect_log.txt');
	return;
}

$logResult = fwrite($logFileHandle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if (!$logResult) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл b0_largus_redirect_log.txt');
	fclose($logFileHandle);
	return;
}

$sectionId = $params->get('section_id', 0);
$typeId = $params->get('type_id', 0);
$isUpdate = $params['is_update'];

while (($data_string = fgetcsv($sourceFileHandle, 10000, ",")) !== FALSE) {
	$dataArray = [
		'id' => $data_string[0],
		'title' => $data_string[1],
		'alias' => $data_string[2],
		'code' => $data_string[3],
	];
//	b0dd($dataArray);
//	Redirect 301 /accessories/item/4920-avtomodel-lada-largus https://stovesta.ru/accessories/item/5262-avtomodel-lada-largus
	$logStr = $dataArray['id'] . ' / ' . $dataArray['code']. ' : ' . $dataArray['title']. ' - ';
	
	$resultPrefix = getPrefix($typeId, $dataArray);
	
	$targetId = getTargetId($typeId, $dataArray['code']);
	
/*	if ($dataArray['code'] === '04147') {
		b0dd($targetId);
	}*/
	
//	b0dd($targetRecord);
	if ($targetId) {
		$targetRecord= ItemsStore::getRecord($targetId);
		$resultComment = '';
		$resultSuffix = getSuffix($typeId, $targetRecord);
	}
	else {
		$resultComment = '#';
		$resultSuffix = '';
	}
	$resultStr = $resultComment . $resultPrefix . ' ' . $resultSuffix;
	fwrite($targetFileHandle, $resultStr . "\n");
}

fclose($sourceFileHandle);
fclose($targetFileHandle);
fclose($logFileHandle);

$app->enqueueMessage('Файл загружен успешно', 'notice');

function getPrefix (string $typeId, array $data) :string
{
	// Redirect 301 /accessories/item/4920-avtomodel-lada-largus
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			return "Redirect 301 /accessories/item/{$data['id']}-{$data['alias']}";
		case SparepartIds::ID_TYPE:
			return "Redirect 301 /spareparts/item/{$data['id']}-{$data['alias']}";
		case WorkIds::ID_TYPE:
			return "Redirect 301 /repair/item/{$data['id']}-{$data['alias']}";
		default:
			return '';
	}
}

function getTargetId (string $typeId, string $code) : ?string
{
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			$fieldId = AccessoryIds::ID_PRODUCT_CODE;
			break;
		case SparepartIds::ID_TYPE:
			$fieldId = SparepartIds::ID_PRODUCT_CODE;
			break;
		case WorkIds::ID_TYPE:
			$fieldId = WorkIds::ID_SERVICE_CODE;
			break;
		default:
			$fieldId = '';
			break;
	}
	
	$db  = JFactory::getDbo();
	$query = "SELECT record_id FROM #__js_res_record_values WHERE field_id={$fieldId} AND field_value={$code}";
	$db->setQuery($query);
	$targetId = $db->loadResult();
	return $targetId;
}

function getSuffix (string $typeId, object $record) :string
{
	// https://stovesta.ru/accessories/item/5262-avtomodel-lada-largus
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			return "https://stovesta.ru/accessories/item/{$record->id}-{$record->alias}";
		case SparepartIds::ID_TYPE:
			return "https://stovesta.ru/spareparts/item/{$record->id}-{$record->alias}";
		case WorkIds::ID_TYPE:
			return "https://stovesta.ru/repair/item/{$record->id}-{$record->alias}";
		default:
			return '';
	}
}

