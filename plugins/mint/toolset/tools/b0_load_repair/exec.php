<?php
include_once JPATH_ROOT.'/components/com_cobalt/api.php';

$app = JFactory::getApplication();

$fileName = JPATH_ROOT . '/uploads/'.$params->get('files');
//$logs = [];
$file_handle = fopen($fileName, "r");
if ($file_handle == false) {
	//$logs[] = 'Logan-Shop SiteMap - Ошибка открытия файла '. $fileName;
	$app->enqueueMessage('Ошибка открытия файла ' . $fileName, 'error');
	return;
}

$section_id = $params->get('section_id', 0);
$type_id = $params->get('type_id', 0);
$category= $params->get('category', '');

while (($data_string = fgetcsv($file_handle, 1500, ";")) !== FALSE) {
	// проверка на пустую строку
	if ($data_string[0] == '') continue;
	if ($data_string[0] == '#') continue;
	// проверка на выгрузку на сайт StoVesta
	if ($data_string[7] == 'Нет') continue;

	// Получаем тип данных
	$dataType = getDataType($data_string);
	//$app->enqueueMessage($dataType);
	// Получаем
	$data = getData($dataType, $data_string);
	// Получаем массив данных полей
	$fields = getFields($dataType, $data_string);
	// Получаем массив категорий
	$categories = getCategories($dataType, $category);
	// Создаем статью в БД
	/** @var array $data массив значений столбцов таблицы js_res_record кроме fields, categories и tags*/
	/** @var integer $section_id идентификатор раздела */
	/** @var integer $type_id идентификатор типа контента */
	/** @var array $fields массив значений полей */
	/** @var array $categories массив категорий */
	CobaltApi::createRecord($data, $section_id, $type_id, $fields, $categories);
	//CobaltApi::touchRecord(0, $section_id, $type_id, $data, $fields, $categories);
}

fclose($file_handle);

$app->enqueueMessage('Раздел загружен успешно', 'notice');

// Получает тип данных из строки CSV файла
/**
 * @param array $data_string
 *
 * @return mixed
 *
 * @since version
 */
function getDataType (array $data_string): string
{
	return $data_string[8];
}

/**
 * @param integer $dataType тип данных
 * @param string $data_string текущая строка из файла загрузки
 *
 * @return array массив данных полей
 *
 * @since version
 */
function getData (string $dataType, array $data_string = []) :array
{
	$models = getModels($dataType);
	$configs = getConfigs($dataType);
	$title = $data_string[0];
	// удаляем (с/у)
	$title = trim(str_ireplace('(с/у)', '', $title));
	//вставляем Lada
	$pos = mb_strpos($title, 'Vesta')-1;
	$title = mb_substr($title, 0, $pos) . ' Lada' . mb_substr($title, $pos);


	if ($data_string[9]) {
		$metaDescr = $data_string[9] . ' Lada' . ' ' . $models . ' ' . $configs;
	}
	else {
		$metaDescr = $title;
	}

	$data = [
		'title' => $title,
		'meta_descr' => $metaDescr,
		'access' => 1,
		'published' => 1
	];
	return $data;
}

function getFields (string $dataType, array $dataString) :array
{
	switch ($dataType) {
		case '0':
			$fields = [
				33 => $dataString[3],       // ID_1C
				34 => $dataString[2],       // Подзаголовок
				37 => $dataString[1],       // Цена
				67 => $dataString[1]*0.97,  // Цена по стандартной карте
				68 => $dataString[1]*0.95,  // Цена по серебряной карте
				69 => $dataString[1]*0.93,  // Цена по золотой карте
				66 => -1,   // Спецпредложение
				// Модели
				39 => ['Lada Vesta sedan', 'Lada Vesta SW', 'Lada XRay'],
				// Года выпуска
				40 => ['2015', '2016', '2017'],
				// Моторы
				41 => ['ВАЗ-21129 (1.6 16V)', 'H4M (1.6 16V)', 'ВАЗ-21179 (1.8 16V)'],
			];
			break;
		case '1':
			$fields = [
				33 => $dataString[3],    // ID_1C
				34 => $dataString[2],    // Подзаголовок
				37 => $dataString[1],    // Цена
				67 => $dataString[1]*0.97,  // Цена по стандартной карте
				68 => $dataString[1]*0.95,  // Цена по серебряной карте
				69 => $dataString[1]*0.93,  // Цена по золотой карте
				66 => -1,   // Спецпредложение
				// Модели
				39 => ['Lada Vesta sedan', 'Lada Vesta SW', 'Lada XRay'],
				// Года выпуска
				40 => ['2015', '2016', '2017'],
				// Моторы
				41 => ['ВАЗ-21129 (1.6 16V)', 'H4M (1.6 16V)', 'ВАЗ-21179 (1.8 16V)'],
			];
			break;
		default:
			$fields = [];
	}
	return $fields;
}

function getCategories (string $dataType, string $category) :array
{
	switch ($dataType)
	{
		case '0':   //Logan, Sandero, Duster, Kaptur
			switch ($category) {
				case 'engine':
					$categories = [78, 79, 80];
					break;
				case 'steering':
					$categories = [81, 82,83];
					break;
				case 'run':
					$categories = [84, 85, 86];
					break;
				case 'transmission':
					$categories = [87, 88, 89];
					break;
				case 'braking':
					$categories = [90, 91, 92];
					break;
				case 'body':
					$categories = [93, 94, 95];
					break;
				case 'salon':
					$categories = [96, 97, 98];
					break;
				case 'electric':
					$categories = [99, 100, 101];
					break;
				case 'tires':
					$categories = [102, 103, 104];
					break;
				case 'reglament':
					$categories = [105, 106, 107];
					break;
				default:
					$categories = [];
			}
			break;
		case '1':   // Logan, Sandero
			switch ($category) {
				case 'engine':
					$categories = [78, 79, 80];
					break;
				case 'steering':
					$categories = [81, 82,83];
					break;
				case 'run':
					$categories = [84, 85, 86];
					break;
				case 'transmission':
					$categories = [87, 88, 89];
					break;
				case 'braking':
					$categories = [90, 91, 92];
					break;
				case 'body':
					$categories = [93, 94, 95];
					break;
				case 'salon':
					$categories = [96, 97, 98];
					break;
				case 'electric':
					$categories = [99, 100, 101];
					break;
				case 'tires':
					$categories = [102, 103, 104];
					break;
				case 'reglament':
					$categories = [105, 106, 107];
					break;
				default:
					$categories = [];
			}
			break;
		default:
			$categories = [];
	}
	return $categories;
}

// Возвращает список моделей в зависимости от типа данных
function getModels (string $dataType) :string
{
	$models = 'Vesta sedan, Vesta SW, XRay';
	return $models;
}

/**
 * @param integer $dataType тип данных
 *
 * @return string строка кофигурации
 *
 * @since version
 */
function getConfigs (string $dataType) :string
{
	$configs = '1.6 / 1.8 с 2015 года';
	return $configs;
}
