<?php
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.fixtures');
//include_once JPATH_ROOT.'/components/com_cobalt/api.php';

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

$targetFilePath = JPATH_ROOT . "/uploads/b0_largus_images.csv";
$targetFileHandle = fopen($targetFilePath, 'ab+');
if (!$targetFileHandle) {
	$app->enqueueMessage('B0 Largus redirect- Ошибка открытия файла b0_largus_images.csv');
	return;
}

/*$logFilePath = JPATH_ROOT . "/logs/b0_largus_images_log.txt";
$logFileHandle = fopen($logFilePath, 'ab+');
if (!$logFileHandle) {
	$app->enqueueMessage('B0 Largus redirect - Ошибка открытия файла b0_largus_images_log.txt');
	return;
}

$logResult = fwrite($logFileHandle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if (!$logResult) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл b0_largus_redirect_log.txt');
	fclose($logFileHandle);
	return;
}*/

$sectionId = $params->get('section_id', 0);
$typeId = $params->get('type_id', 0);
$isUpdate = $params['is_update'];

while (($data_string = fgetcsv($sourceFileHandle, 10000, ",")) !== FALSE) {
	$dataArray = [
		'id' => $data_string[0],
		'title' => $data_string[1],
		'alias' => $data_string[2],
		'code' => $data_string[3],
		'image' => $data_string[4],
	];
//	b0dd($dataArray);
//	Redirect 301 /accessories/item/4920-avtomodel-lada-largus https://stovesta.ru/accessories/item/5262-avtomodel-lada-largus
//	$logStr = $dataArray['id'] . ' / ' . $dataArray['code']. ' : ' . $dataArray['title']. ' - ';
	
	$resultPrefix = getPrefix($typeId, $dataArray);
	
	$targetId = getTargetId($typeId, $dataArray['code']);
	
//	b0dd($targetRecord);
	if ($targetId) {
		$targetRecord= ItemsStore::getRecord($targetId);
		$resultComment = '';
		$resultSuffix = getSuffix($typeId, $targetRecord, $dataArray);
	}
	else {
		$resultComment = '#';
		$resultSuffix = '';
	}
	$resultArray = array_merge($resultPrefix, $resultSuffix);
	fputcsv($targetFileHandle, $resultArray, ',');
//	fwrite($targetFileHandle, $resultStr . "\n");
}

fclose($sourceFileHandle);
fclose($targetFileHandle);
//fclose($logFileHandle);

$app->enqueueMessage('Файл загружен успешно', 'notice');

function getPrefix (string $typeId, array $data) :array
{
	// Redirect 301 /accessories/item/4920-avtomodel-lada-largus
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			return [$data['title'], "https://largus-shop.spb.ru/accessories/item/{$data['id']}-{$data['alias']}"];
		case SparepartIds::ID_TYPE:
			return [$data['title'], "https://largus-shop.spb.ru/spareparts/item/{$data['id']}-{$data['alias']}"];
		default:
			return [];
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

function getSuffix (string $typeId, object $record, array $data) :array
{
	// https://stovesta.ru/accessories/item/5262-avtomodel-lada-largus
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:
			return ["https://stovesta.ru/accessories/item/{$record->id}-{$record->alias}", "https://largus-shop.spb.ru/{$data['image']}"];
		case SparepartIds::ID_TYPE:
			return ["https://stovesta.ru/spareparts/item/{$record->id}-{$record->alias}", "https://largus-shop.spb.ru/{$data['image']}"];
		default:
			return [];
	}
}

