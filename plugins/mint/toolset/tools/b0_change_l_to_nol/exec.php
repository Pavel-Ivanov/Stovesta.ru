<?php
include_once JPATH_ROOT.'/components/com_cobalt/api.php';
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Work.WorkIds');
JImport('b0.fixtures');


const POS_TITLE = 1;
const POS_CODE_L = 2;
const POS_CODE_NOL = 4;

/** @var JRegistry $params */

//$productCodeSparepart = SparepartKeys::KEY_PRODUCT_CODE;
//$productCodeAccessory = AccessoryKeys::KEY_PRODUCT_CODE;
//$serviceCode = WorkKeys::KEY_SERVICE_CODE;

$app = JFactory::getApplication();
$sourceFileName = JPATH_ROOT . '/uploads/'.$params->get('files');
$sourceFileHandle = fopen($sourceFileName, "r");
if (!$sourceFileHandle) {
    $app->enqueueMessage('Ошибка открытия файла ' . $sourceFileName, 'error');
    return;
}

$logFileName = JPATH_ROOT . "/logs/b0_change_code_l_to_nol.txt";
$logFileHandle = fopen($logFileName, 'ab+');
if (!$logFileHandle) {
    $app->enqueueMessage('Ошибка открытия файла b0_change_code_l_to_nol.txt');
    return;
}

$logResult = fwrite($logFileHandle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if (!$logResult) {
    $app->enqueueMessage('Ошибка записи в файл b0_change_code_l_to_nol.txt');
    fclose($logFileHandle);
    fclose($sourceFileHandle);
    return;
}

$isUpdate = $params['is_update'];

while (($data_string = fgetcsv($sourceFileHandle, 1500, ";")) !== FALSE) {
//    b0dd($data_string);
    if ($data_string[POS_CODE_L] === '') {
        $logStr = $data_string[POS_TITLE] . ' : ' . '!!! пустой код в исходном файле, пропускаем';
        fwrite($logFileHandle,$logStr . "\n");
        continue;
    }
    $codeL = str_pad($data_string[POS_CODE_L], 5, '0', STR_PAD_LEFT);
    $logStr = $codeL . ' - ' . $data_string[POS_TITLE] . ' : ';

    $recordId = getRecordId($codeL);
//    b0dd($recordId);
    if ($recordId === 0) {
        $logStr .= 'не найдена, пропускаем';
        fwrite($logFileHandle,$logStr . "\n");
        continue;
    }

    $record = ItemsStore::getRecord($recordId);
//    b0dd($record);

//    $fields = json_decode($record->fields, true, 512, JSON_THROW_ON_ERROR);
    $fields = [];
    switch ($record->type_id) {
        case SparepartIds::ID_TYPE:
            $fields[SparepartIds::ID_PRODUCT_CODE] = $data_string[POS_CODE_NOL];
            break;
        case AccessoryIds::ID_TYPE:
            $fields[AccessoryIds::ID_PRODUCT_CODE] = $data_string[POS_CODE_NOL];
            break;
    }

    if ($isUpdate === '1') {
        if (!CobaltApi::updateRecord($recordId, [], $fields)) {
            $logStr .= 'ошибка обновления записи';
            fwrite($logFileHandle,$logStr . "\n");
            continue;
        }
        $logStr .= 'заменено';
    }
    else {
        $logStr .= 'тест';
    }
    fwrite($logFileHandle,$logStr . "\n");
//    JExit();
}
fclose($sourceFileHandle);
fclose($logFileHandle);
$app->enqueueMessage('Коды изменены успешно', 'notice');

function getRecordId(string $code): int
{
    $db = JFactory::getDbo();
    $query = "SELECT record_id FROM #__js_res_record_values";
    $query .= " WHERE (field_id IN (4, 74) && field_value='$code')";
    $db->setQuery($query);
    return $db->loadResult() ?? 0;
}
