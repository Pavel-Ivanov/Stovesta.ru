<?php
include_once JPATH_ROOT.'/components/com_cobalt/api.php';
JImport('b0.Work.WorkIds');
JImport('b0.fixtures');

const POS_SERVICE_CODE = 0;
const POS_TITLE = 1;
const POS_SUBTITLE = 2;
const POS_CATEGORY = 3;
const POS_PRICE_GENERAL = 4;
const POS_PRICE_SIMPLE = 5;
const POS_PRICE_SILVER = 6;
const POS_PRICE_GOLD = 7;

$app = JFactory::getApplication();

$fileName = JPATH_ROOT . '/uploads/'.$params->get('files');
//$logs = [];
$file_handle = fopen($fileName, "r");
if ($file_handle == false) {
	//$logs[] = 'Logan-Shop SiteMap - Ошибка открытия файла '. $fileName;
	$app->enqueueMessage('Ошибка открытия файла ' . $fileName, 'error');
	return;
}

$section_id = (int)$params->get('section_id', 0);
//b0dd($section_id);
$type_id = (int)$params->get('type_id', 0);
//b0dd($type_id);
//$category= $params->get('category', '');
$category= 'Lada Granta FL';
//b0dd($category);

while (($data_string = fgetcsv($file_handle, 1500, ";")) !== FALSE) {
	if ($data_string[POS_SERVICE_CODE] === '') {
		continue;
	}
	if ($data_string[POS_SERVICE_CODE] === '#') {
		continue;
	}
	// Получаем
	$data = getData($data_string);
//	b0dd($data);
	// Получаем массив данных полей
	$fields = getFields($data_string);
//	b0dd($fields);
	// Получаем массив категорий
//	$categories = getCategories();
	$categories[] = 165;
//	b0dd($categories);
	// Создаем статью в БД
	$recordId = CobaltApi::createRecord((array) $data, $section_id, $type_id, (array) $fields, (array) $categories);
	//b0dd($recordId);
/*	if ($recordId){
		continue;
	}
	else {
		echo 'Error';
	}*/
	//CobaltApi::touchRecord(0, $section_id, $type_id, $data, $fields, $categories);
}
fclose($file_handle);
$app->enqueueMessage('Раздел загружен успешно', 'notice');

function getData (array $data_string = []) :array
{
	// удаляем (с/у)
	$title = trim(str_ireplace('(с/у)', '', $data_string[POS_TITLE]));
//	$metaDescr = mb_substr($title, 0, mb_strrpos($title, '-'));
	$metaDescr = $title;
	// добавляем модель и мотор
	$title = trim(str_ireplace('1.6 8V 2WD ', 'Granta (1.6 8V, 2WD)', $title));

	$data = [
		'title' => $title,
		'meta_descr' => $metaDescr,
		'access' => 1,
		'published' => 1
	];
	return $data;
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
