<?php
defined('_JEXEC') or die();
JImport('b0.Yml.YmlOffer');
JImport('b0.Yml.YmlConfig');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
//require_once JPATH_ROOT . '/libraries/b0/Yml/yml-categories.php';
/*
 *  $mode = 'full' - полный - для Товары и Предложения
 */
class Yml
{
	private array $logs = [];

    public string $mode;
    // Разделы файла
    public string $name;
    public string $company;
    public string $url;
    public string $currency;
    public string $currencyRate;
    public array $categories;
//    public array $deliveryOptions;
    public array $offers;

	public function __construct($mode)
	{
		/** @var array $ymlCategories */

		require_once JPATH_ROOT . '/libraries/b0/Yml/yml-categories.php';

		$this->mode = $mode;
		$this->name            = YmlConfig::YML_NAME;
		$this->company         = YmlConfig::YML_COMPANY;
		$this->url             = YmlConfig::YML_URL;
		$this->currency        = YmlConfig::YML_CURRENCY;
		$this->currencyRate    = YmlConfig::YML_CURRENCY_RATE;
//		$this->categories      = $this->setCategories();
		$this->categories      = $ymlCategories;
//		$this->deliveryOptions = YmlConfig::YML_DELIVERY_OPTIONS;
		$this->offers          = $this->setOffers();
	}
	
	// Создает массив категорий
/*	private function setCategories():array
	{
		$categories = [];
		$ymlFieldId = YmlConfig::YML_FIELD_ID;
		$db    = JFactory::getDbo();
		$query = 'SELECT id, name, parent_id FROM #__js_res_field_multilevelselect WHERE field_id=' . $ymlFieldId;
		$db->setQuery($query);
		$list = $db->loadObjectList();
		$categories['1'] = [
			'id' => '1',
			'name' => 'Авто',
		];
		foreach ($list as $item) {
			$categories[$item->id] = [
				'id' => $item->id,
				'name' => $item->name,
				'parent' => $item->parent_id
			];
		}
		return $categories;
	}*/
	
	// Создает массив товаров
	private function setOffers(): array
	{
		$offers = [];
		
		/* получаем записи из БД */
		$list = $this->getItems();
		if (!$list) {
			$this->logs[] = 'Ошибка запроса к базе данных';
			return [];
		}
		
		/* цикл по записям БД */
		foreach ($list as $item) {
			$offers[] = new YmlOffer($item, $this->categories, $this->mode);
		}
		return $offers;
	}
	
	private function getItems()
	{
		$sectionSparepartId = SparepartIds::ID_SECTION;
		$sectionAccessoryId  = AccessoryIds::ID_SECTION;
		$fieldImageSparepartId = SparepartIds::ID_IMAGE;
		$fieldImageAccessoryId = AccessoryIds::ID_IMAGE;

		$db = JFactory::getDbo();
        /*
         * раздел - Аксессуары или Запчасти
         * реальное фото
         * запись опубликована
         */
        $query = "SELECT id, title, section_id, meta_descr, alias, categories, fields FROM #__js_res_record
            WHERE (
                section_id IN ($sectionSparepartId, $sectionAccessoryId) AND
                id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id=$fieldImageSparepartId OR field_id=$fieldImageAccessoryId)) AND
                published = 1
            )";

		if (YmlConfig::YML_ITEMS_LIMIT > 0) {
			$query .= ' LIMIT 0,' . $this->getYmlConfigValue('YML_ITEMS_LIMIT');
		}
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	/*
	 * Выводит лист офферов
	 */
	public function render(): bool
	{
		/** @var mixed $handle */
        $handle = fopen(JPATH_ROOT . YmlConfig::YML_FILE_PATH_FULL, 'w+b');
		if ($handle === false) {
			$logs[] = 'Ошибка открытия файла yml';
			return false;
		}

		// Выводим шапку-заголовок
        $title = $this->getTitle();
        $res = fwrite($handle, $title);
        if ($res === false) {
            $logs[] = 'Ошибка записи в файл yml';
            fclose($handle);
            return false;
        }

		// Выводим Офферы
		foreach ($this->offers as $offer) {
			$content = $offer->renderOffer($this->mode);
			$res     = fwrite($handle, $content);
			if ($res === false) {
				$logs[] = 'Ошибка записи в файл yml';
				fclose($handle);
				return false;
			}
		}

		// Выводим Footer
        $footer = "</offers>\n" . "</shop>\n" . '</yml_catalog>';
        fwrite($handle, $footer);

		fclose($handle);
		$logs[] = 'Файл yml сформирован';
		return true;
	}

	private function getTitle(): string
	{
        $categoriesXml = $this->getCategoriesXml();

        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">
<yml_catalog date=\"{$this->getCurrentDate()}\">
<shop>
<name>{$this->getYmlConfigValue('YML_NAME')}</name>
<company>{$this->getYmlConfigValue('YML_COMPANY')}</company>
<url>{$this->getYmlConfigValue('YML_URL')}</url>
<currencies>
<currency id=\"{$this->getYmlConfigValue('YML_CURRENCY')}\" rate=\"{$this->getYmlConfigValue('YML_CURRENCY_RATE')}\"/>
</currencies>
$categoriesXml
<offers>";
	}
    private function getCategoriesXml(): string
    {
        $categoriesXml = '';
        foreach ($this->categories as $id => $category) {
            if ($category['name']) {
                $parentAttribute = isset($category['parent']) ? ' parentId="' . $category['parent'] . '"' : '';
                $categoriesXml .= "<category id=\"$id\"$parentAttribute>{$category['name']}</category>\n";
            }
        }
        return "<categories>\n$categoriesXml</categories>\n";
    }

    private function getYmlConfigValue(string $constantName): string
    {
        return constant("YmlConfig::$constantName");
    }

    private function getCurrentDate(): string
    {
        return date('Y-m-d H:i');
    }

}
