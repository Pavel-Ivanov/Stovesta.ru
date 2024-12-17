<?php
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Largus.LargusSparepartIds');
JImport('b0.Largus.LargusAccessoryIds');
JImport('b0.fixtures');
include_once JPATH_ROOT.'/components/com_cobalt/api.php';

$app = JFactory::getApplication();

$fileName = JPATH_ROOT . '/uploads/'.$params->get('files');
//$logs = [];
$file_handle = fopen($fileName, 'rb');
if (!$file_handle) {
	$app->enqueueMessage('Ошибка открытия файла ' . $fileName, 'error');
	return;
}

$logFilePath = JPATH_ROOT . "/logs/b0_load_largus_log.txt";
$logFileHandle = fopen($logFilePath, 'ab+');
if (!$logFileHandle) {
	$app->enqueueMessage('B0 load Largus - Ошибка открытия файла b0_load_largus_log.txt');
	return;
}

$res = fwrite($logFileHandle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
if (!$res) {
	$app->enqueueMessage('B0 add value - Ошибка записи в файл add_value_log.txt');
	fclose($logFileHandle);
	return;
}


$sectionId = $params->get('section_id', 0);
$typeId = $params->get('type_id', 0);
$isUpdate = $params['is_update'];

//$category= $params->get('category', '');

while (($data_string = fgetcsv($file_handle, 10000, ",")) !== FALSE) {
	// Преобразовываем строку в массив
	$dataArray = [
		'id' => $data_string[0],
		'title' => $data_string[1],
		'hits' => $data_string[2],
		'metaDescr' => $data_string[3],
		'metaKey' => $data_string[4],
		'alias' => $data_string[5],
		'accessKey' => $data_string[6],
		'fieldsData' => $data_string[7],
		'fields' => json_decode($data_string[8], true, 512, JSON_THROW_ON_ERROR),
		'categories' => $data_string[9],
//		'categories' => '',
	];
//	b0dd($dataArray);
	$str = $dataArray['id'] . ' : ' . $dataArray['title'];
	// Необходимо проверить на уникальность Код товара
	$productCode = getProductCode($typeId, $dataArray['fields']);
	// Получаем
	$data = getData($typeId, $dataArray);
//	b0dd($data);
	
	// Получаем массив данных полей
	$fields = getFields($typeId, $dataArray['fields']);
//	b0dd($fields);
	
	// Получаем массив категорий
	$categories = getCategories($typeId, $dataArray['categories']);
//	b0dd($categories);
	
	// Создаем статью в БД
	/** @var array $data массив значений столбцов таблицы js_res_record кроме fields, categories и tags*/
	/** @var integer $sectionId идентификатор раздела */
	/** @var integer $typeId идентификатор типа контента */
	/** @var array $fields массив значений полей */
	/** @var array $categories массив категорий */
//	CobaltApi::createRecord($data, $sectionId, $typeId, $fields, $categories);
	//CobaltApi::touchRecord(0, $section_id, $type_id, $data, $fields, $categories);
	if ($isUpdate === '1') {
		if (!CobaltApi::createRecord($data, (int)$sectionId, (int)$typeId, $fields, $categories)) {
			$str .= ' / ошибка записи';
			fwrite($logFileHandle,$str . "\n");
			continue;
		}
		$str .= ' / добавлено';
	}
	else {
		$str .= ' / тест';
	}
	
	fwrite($logFileHandle, $str . "\n");
}
fwrite($logFileHandle, 'Завершено успешно' . "\n");
fclose($logFileHandle);
fclose($file_handle);

$app->enqueueMessage('Раздел загружен успешно', 'notice');

function getProductCode (string $typeId, array $dataFields) :string
{
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:   // Аксессуар - '7'
			return $dataFields[LargusAccessoryIds::ID_PRODUCT_CODE];
		case SparepartIds::ID_TYPE:   // Запчасть - '1'
			return $dataFields[LargusSparepartIds::ID_PRODUCT_CODE];
		default:
			return '';
	}
}

function checkProductCode (string $typeId, string $productCode) :bool
{
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:   // Аксессуар - '7'
			return false;
		case SparepartIds::ID_TYPE:   // Запчасть - '1'
			return false;
		default:
			return '';
	}
}

function getData (string $typeId, array $data) :array
{
	$data = [
		'title' => $data['title'],
		'access' => 1,
		'published' => 1,
		'hits' => $data['hits'],
		'meta_descr' => $data['metaDescr'],
		'meta_key' => $data['metaKey'],
		'alias' => $data['alias'],
	];
	return $data;
}

function getFields (string $typeId, array $dataFields) :array
{
	switch ($typeId) {
		case AccessoryIds::ID_TYPE:   // Аксессуар - '7'
			$fields = [
				AccessoryIds::ID_SUBTITLE        => $dataFields[LargusAccessoryIds::ID_SUBTITLE],       // Подзаголовок
				AccessoryIds::ID_SEARCH_SYNONYMS => $dataFields[LargusAccessoryIds::ID_SEARCH_SYNONYMS],       // Синонимы для поиска
				AccessoryIds::ID_MANUFACTURER    => $dataFields[LargusAccessoryIds::ID_MANUFACTURER],       // Производитель
//				AccessoryIds::ID_IMAGE           => $dataFields[LargusAccessoryIds::ID_IMAGE],       // Изображение
//				AccessoryIds::ID_IMAGE           => '',       // Изображение
				AccessoryIds::ID_PRODUCT_CODE    => $dataFields[LargusAccessoryIds::ID_PRODUCT_CODE],       // Код товара
				AccessoryIds::ID_VENDOR_CODE     => $dataFields[LargusAccessoryIds::ID_VENDOR_CODE],       // Код производителя
				AccessoryIds::ID_ORIGINAL_CODE     => $dataFields[LargusAccessoryIds::ID_ORIGINAL_CODE],       // Код оригинала
				AccessoryIds::ID_PRICE_GENERAL     => $dataFields[LargusAccessoryIds::ID_PRICE_GENERAL],       // Цена
				AccessoryIds::ID_PRICE_SIMPLE     => $dataFields[LargusAccessoryIds::ID_PRICE_SIMPLE],       // Цена по стандартной карте
				AccessoryIds::ID_PRICE_SILVER     => $dataFields[LargusAccessoryIds::ID_PRICE_SILVER],       // Цена по серебряной карте
				AccessoryIds::ID_PRICE_GOLD     => $dataFields[LargusAccessoryIds::ID_PRICE_GOLD],       // Цена по золотой карте
				AccessoryIds::ID_PRICE_DELIVERY     => $dataFields[LargusAccessoryIds::ID_PRICE_DELIVERY],       // Цена при доставке
				AccessoryIds::ID_IS_SPECIAL     => $dataFields[LargusAccessoryIds::ID_IS_SPECIAL],       // Спецпредложение
				AccessoryIds::ID_PRICE_SPECIAL     => $dataFields[LargusAccessoryIds::ID_PRICE_SPECIAL],       // Цена спецпредложения
				AccessoryIds::ID_IS_ORIGINAL     => $dataFields[LargusAccessoryIds::ID_IS_ORIGINAL],       // Оригинал
				AccessoryIds::ID_IS_BY_ORDER     => $dataFields[LargusAccessoryIds::ID_IS_BY_ORDER],       // Под заказ
				AccessoryIds::ID_SEDOVA     => $dataFields[LargusAccessoryIds::ID_SEDOVA],       // Седова
				AccessoryIds::ID_KHIMIKOV     => $dataFields[LargusAccessoryIds::ID_KHIMIKOV],       // Химиков
				AccessoryIds::ID_ZHUKOVA     => $dataFields[LargusAccessoryIds::ID_ZHUKOVA],       // Жукова
				AccessoryIds::ID_KULTURY     => $dataFields[LargusAccessoryIds::ID_KULTURY],       // Культуры
				AccessoryIds::ID_PLANERNAYA     => $dataFields[LargusAccessoryIds::ID_PLANERNAYA],       // Планерная
				AccessoryIds::ID_CHARACTERISTICS     => $dataFields[LargusAccessoryIds::ID_CHARACTERISTICS],       // Характеристики
				AccessoryIds::ID_DESCRIPTION     => $dataFields[LargusAccessoryIds::ID_DESCRIPTION],       // Описание
				AccessoryIds::ID_GALLERY     => $dataFields[LargusAccessoryIds::ID_GALLERY],       // Галерея
				AccessoryIds::ID_VIDEO     => $dataFields[LargusAccessoryIds::ID_VIDEO],       // Видео
				AccessoryIds::ID_MODEL     => $dataFields[LargusAccessoryIds::ID_MODEL],       // Модель
				AccessoryIds::ID_GENERATION     => $dataFields[LargusAccessoryIds::ID_GENERATION],       // Поколение
				AccessoryIds::ID_YEAR     => $dataFields[LargusAccessoryIds::ID_YEAR],       // Год выпуска
				AccessoryIds::ID_BODY     => $dataFields[LargusAccessoryIds::ID_BODY],       // Кузов
				AccessoryIds::ID_MOTOR     => $dataFields[LargusAccessoryIds::ID_MOTOR],       // Мотор
				AccessoryIds::ID_DRIVE     => '2WD',       // Привод
//				AccessoryIds::ID_ANALOGS     => $dataFields[LargusAccessoryIds::ID_ANALOGS],       // Аналоги
//				AccessoryIds::ID_ASSOCIATED     => $dataFields[LargusAccessoryIds::ID_ASSOCIATED],       // Сопутствующие
//				AccessoryIds::ID_WORKS     => $dataFields[LargusAccessoryIds::ID_WORKS],       // Работы
//				AccessoryIds::ID_ARTICLES     => $dataFields[LargusAccessoryIds::ID_ARTICLES],       // Статьи
				AccessoryIds::ID_YM_UPLOAD_ENABLE     => -1,       // Выгрузка на Яндекс Маркет
				AccessoryIds::ID_YM_TITLE     => '',       // Название на Яндекс Маркет
//				AccessoryIds::ID_YM_CATEGORY     => '',       // Категория на Яндекс Маркет
				AccessoryIds::ID_YM_IMAGE     => '',       // Изображение на Яндекс Маркет
			];
			break;
		case SparepartIds::ID_TYPE:   // Запчасть - '1'
			$fields = [
				SparepartIds::ID_SUBTITLE        => $dataFields[LargusSparepartIds::ID_SUBTITLE],       // Подзаголовок
				SparepartIds::ID_SEARCH_SYNONYMS => $dataFields[LargusSparepartIds::ID_SEARCH_SYNONYMS],       // Синонимы для поиска
				SparepartIds::ID_MANUFACTURER    => $dataFields[LargusSparepartIds::ID_MANUFACTURER],       // Производитель
//				SparepartIds::ID_IMAGE           => $dataFields[LargusSparepartIds::ID_IMAGE],       // Изображение
//				SparepartIds::ID_IMAGE           => '',       // Изображение
				SparepartIds::ID_PRODUCT_CODE    => $dataFields[LargusSparepartIds::ID_PRODUCT_CODE],       // Код товара
				SparepartIds::ID_VENDOR_CODE     => $dataFields[LargusSparepartIds::ID_VENDOR_CODE],       // Код производителя
				SparepartIds::ID_ORIGINAL_CODE   => $dataFields[LargusSparepartIds::ID_ORIGINAL_CODE],       // Код оригинала
				SparepartIds::ID_PRICE_GENERAL   => $dataFields[LargusSparepartIds::ID_PRICE_GENERAL],       // Цена
				SparepartIds::ID_PRICE_SIMPLE    => $dataFields[LargusSparepartIds::ID_PRICE_SIMPLE],       // Цена по стандартной карте
				SparepartIds::ID_PRICE_SILVER    => $dataFields[LargusSparepartIds::ID_PRICE_SILVER],       // Цена по серебряной карте
				SparepartIds::ID_PRICE_GOLD      => $dataFields[LargusSparepartIds::ID_PRICE_GOLD],       // Цена по золотой карте
				SparepartIds::ID_PRICE_DELIVERY  => $dataFields[LargusSparepartIds::ID_PRICE_DELIVERY],       // Цена при доставке
				SparepartIds::ID_IS_SPECIAL      => $dataFields[LargusSparepartIds::ID_IS_SPECIAL],       // Спецпредложение
				SparepartIds::ID_PRICE_SPECIAL   => $dataFields[LargusSparepartIds::ID_PRICE_SPECIAL],       // Цена спецпредложения
				SparepartIds::ID_IS_ORIGINAL     => $dataFields[LargusSparepartIds::ID_IS_ORIGINAL],       // Оригинал
				SparepartIds::ID_IS_BY_ORDER     => $dataFields[LargusSparepartIds::ID_IS_BY_ORDER],       // Под заказ
				SparepartIds::ID_SEDOVA          => $dataFields[LargusSparepartIds::ID_SEDOVA],       // Седова
				SparepartIds::ID_KHIMIKOV        => $dataFields[LargusSparepartIds::ID_KHIMIKOV],       // Химиков
				SparepartIds::ID_ZHUKOVA         => $dataFields[LargusSparepartIds::ID_ZHUKOVA],       // Жукова
				SparepartIds::ID_KULTURY         => $dataFields[LargusSparepartIds::ID_KULTURY],       // Культуры
				SparepartIds::ID_PLANERNAYA      => $dataFields[LargusSparepartIds::ID_PLANERNAYA],       // Планерная
				SparepartIds::ID_CHARACTERISTICS     => $dataFields[LargusSparepartIds::ID_CHARACTERISTICS],       // Характеристики
				SparepartIds::ID_DESCRIPTION     => $dataFields[LargusSparepartIds::ID_DESCRIPTION],       // Описание
//				SparepartIds::ID_GALLERY     => $dataFields[LargusSparepartIds::ID_GALLERY],       // Галерея
				SparepartIds::ID_VIDEO     => $dataFields[LargusSparepartIds::ID_VIDEO],       // Видео
				SparepartIds::ID_CATEGORY     => $dataFields[LargusSparepartIds::ID_CATEGORY],       // Категория
				SparepartIds::ID_MODEL     => $dataFields[LargusSparepartIds::ID_MODEL],       // Модель
				SparepartIds::ID_GENERATION     => $dataFields[LargusSparepartIds::ID_GENERATION],       // Поколение
				SparepartIds::ID_YEAR     => $dataFields[LargusSparepartIds::ID_YEAR],       // Год выпуска
				SparepartIds::ID_BODY     => $dataFields[LargusSparepartIds::ID_BODY],       // Кузов
				SparepartIds::ID_MOTOR     => $dataFields[LargusSparepartIds::ID_MOTOR],       // Мотор
				SparepartIds::ID_DRIVE     => '2WD',       // Привод
//				SparepartIds::ID_ANALOGS     => $dataFields[LargusAccessoryIds::ID_ANALOGS],       // Аналоги
//				SparepartIds::ID_ASSOCIATED     => $dataFields[LargusAccessoryIds::ID_ASSOCIATED],       // Сопутствующие
//				SparepartIds::ID_WORKS     => $dataFields[LargusAccessoryIds::ID_WORKS],       // Работы
//				SparepartIds::ID_ARTICLES     => $dataFields[LargusAccessoryIds::ID_ARTICLES],       // Статьи
				SparepartIds::ID_YM_UPLOAD_ENABLE     => -1,       // Выгрузка на Яндекс Маркет
				SparepartIds::ID_YM_TITLE     => '',       // Название на Яндекс Маркет
//				SparepartIds::ID_YM_CATEGORY     => '',       // Категория на Яндекс Маркет
				SparepartIds::ID_YM_IMAGE     => '',       // Изображение на Яндекс Маркет
			
			];
			break;
		default:
			$fields = [];
	}
	return $fields;
}

function getCategories (string $typeId, string $categories) :array
{
	switch ($typeId){
		case AccessoryIds::ID_TYPE:   // Аксессуар - '7'
			$cat[] = AccessoryIds::ID_CATEGORY_LARGUS;
			break;
		case SparepartIds::ID_TYPE:   // Запчасть - '1'
			$cat[] = SparepartIds::ID_CATEGORY_LARGUS;
			break;
		default:
			$cat = [];
	}
	return $cat;
}
