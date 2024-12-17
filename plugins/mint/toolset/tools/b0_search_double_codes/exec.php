<?php
include_once JPATH_ROOT.'/components/com_cobalt/api.php';
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Work.WorkIds');
JImport('b0.fixtures');

const POS_TITLE = 0;
const POS_CODE = 1;
const POS_CODE_SITE = 2;
const POS_TYPE = 3;
const POS_GROUP = 4;
const POS_CODE_SITE_NEW = 5;

$app = JFactory::getApplication();

$sourceFileName = JPATH_ROOT . '/uploads/'.$params->get('files');
$sourceFileHandle = fopen($sourceFileName, "r");
if (!$sourceFileHandle) {
	$app->enqueueMessage('Ошибка открытия файла ' . $sourceFileName, 'error');
	return;
}

$logFileName = JPATH_ROOT . "/logs/b0_search_double_codes_log.txt";
$logFileHandle = fopen($logFileName, 'ab+');
if (!$logFileHandle) {
    $app->enqueueMessage('Ошибка открытия файла b0_search_double_codes_log.txt');
    return;
}

$logResult = fwrite($logFileHandle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if (!$logResult) {
    $app->enqueueMessage('Ошибка записи в файл b0_search_double_codes_log.txt');
    fclose($logFileHandle);
    fclose($sourceFileHandle);
    return;
}

while (($data_string = fgetcsv($sourceFileHandle, 1500, ";")) !== FALSE) {
	if ($data_string[POS_CODE_SITE] === '') {
        $logStr = $data_string[POS_TITLE] . ' : ' . '!!! пустой код сайта в исходном файле, пропускаем';
        fwrite($logFileHandle,$logStr . "\n");
		continue;
	}
    $codeSite = str_pad($data_string[POS_CODE_SITE], 5, '0', STR_PAD_LEFT);
//    $logStr = $codeSite . ' - ' . $data_string[POS_TITLE] . ' : ';

    $db = JFactory::getDbo();
    $query = "SELECT record_id FROM #__js_res_record_values WHERE (field_id IN (4, 74, 33) && field_value='{$codeSite}')";
    $db->setQuery($query);
    $result = $db->loadObjectList();
    if (count($result) > 1) {
        $logStr = $codeSite . ' - ' . $data_string[POS_TITLE] . "\n";

        foreach ($result as $item) {
            $query = "SELECT * FROM #__js_res_record WHERE id={$item->record_id}";
            $db->setQuery($query);
            $record = $db->loadObject();

//            b0dd($record);
            if ($record->published === '1') {
                $logStr .= '    ' . $record->title . ' - https://stovesta.ru/spareparts/item/' . $record->id . '-' . $record->alias . "\n";
            }
            else {
                $logStr .= '    !!! Удален ' . $record->title . ' - https://stovesta.ru/spareparts/item/' . $record->id . '-' . $record->alias . "\n";
            }
        }
//        b0dd($result);
//        $logStr .= count($result);
        fwrite($logFileHandle,$logStr . "\n");
    }

}
fclose($sourceFileHandle);
fclose($logFileHandle);
$app->enqueueMessage('Коды изменены успешно', 'notice');

function getRecordId(string $code, string $type): int
{
    $db = JFactory::getDbo();
    $query = "SELECT record_id FROM #__js_res_record_values";
    if ($type === 'Запас') {
        $query .= " WHERE (field_id IN (4, 74) && field_value='{$code}')";
    }
    else {
        $query .= " WHERE (field_id IN (33) && field_value='{$code}')";
    }
    $db->setQuery($query);
    return $db->loadResult() ?? 0;
}

function checkType(string $sourceType, string $itemType): bool
{
    switch ($sourceType) {
        case 'Запас':
            return ($itemType == '1' || $itemType == '7');
        case 'Работа':
            return $itemType == '5';
    }
    return true;
}

function getData (array $data_string = []) :array
{
//	$title = trim($data_string[POS_TITLE]);
//    $code = $data_string[POS_CODE];
//    $codeSite = $data_string[POS_CODE_SITE];
//    $codeSiteNew = $data_string[POS_CODE_SITE_NEW];

    return [
        'title' => trim($data_string[POS_TITLE]),
        'code' => $data_string[POS_CODE],
        'codeSite' => $data_string[POS_CODE_SITE],
        'codeSiteNew' => $data_string[POS_CODE_SITE_NEW],
    ];
}

function getFields (array $dataString) :array
{
			$fields = [
				WorkIds::ID_SERVICE_CODE => $dataString[POS_SERVICE_CODE],
				WorkIds::ID_APPROXIMATE_TIME => [],
				WorkIds::ID_SUBTITLE => $dataString[POS_SUBTITLE],
				WorkIds::ID_SEARCH_SYNONYMS => '',
				WorkIds::ID_IMAGE => '',
				WorkIds::ID_PRICE_GENERAL => $dataString[POS_PRICE_GENERAL],
				WorkIds::ID_PRICE_SIMPLE => $dataString[POS_PRICE_SIMPLE],
				WorkIds::ID_PRICE_SILVER => $dataString[POS_PRICE_SILVER],
				WorkIds::ID_PRICE_GOLD => $dataString[POS_PRICE_GOLD],
				WorkIds::ID_PRICE_FIRST_VISIT => (string) ($dataString[POS_PRICE_GENERAL] * 0.8),
				WorkIds::ID_IS_SPECIAL => -1,
				WorkIds::ID_PRICE_SPECIAL => 0,
				WorkIds::ID_MODEL => ['Lada Granta FL'],
				WorkIds::ID_CATEGORY => [$dataString[POS_CATEGORY]],
				WorkIds::ID_GENERATION => ['Lada Granta FL'],
				WorkIds::ID_YEAR => ['2018', '2019', '2020'],
				WorkIds::ID_MOTOR => ['1.6 8V'],
				WorkIds::ID_DRIVE => ['2WD'],
			];
	return $fields;
}

function getCategories () : array
{
	return [(int) WorkIds::ID_CATEGORY_GRANTA_FL];
}
