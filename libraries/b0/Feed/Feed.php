<?php
defined('_JEXEC') or die();
JImport('b0.Feed.FeedOffer');
JImport('b0.Feed.FeedConfig');
JImport('b0.Feed.FeedConfigCategories');
class Feed
{
	private array $config = [];
	private string $sectionId;
	private string $fieldAvailability;
	private string $fieldModel;
	private string $modelName;
	private int $limit;
	
	public array $offers = [];
	
	private array $logs = [];
	public string $mode;
	// Разделы файла
	public string $name;
	public string $company;
	public string $url;
	public string $currency;
	public string $currencyRate;
	public array $deliveryOptions;

	public function __construct($config)
	{
		$this->config = $config;
		$this->sectionId = $config['sectionId'];
		$this->fieldAvailability = $config['fieldAvailability'];
		$this->fieldModel = $config['fieldModel'];
		$this->modelName = $config['modelName'];
		$this->limit = $config['limit'];
		
//		$this->name            = FeedConfig::YML_NAME;
//		$this->company         = FeedConfig::YML_COMPANY;
//		$this->url             = FeedConfig::YML_URL;
//		$this->currency        = FeedConfig::YML_CURRENCY;
//		$this->currencyRate    = FeedConfig::YML_CURRENCY_RATE;
//		$this->deliveryOptions = FeedConfig::YML_DELIVERY_OPTIONS;
//		$this->offers          = $this->getOffers();
		
		$this->offers = $this->getOffers();
//		b0dd($this->offers);
	}
	
	// Создает массив товаров
	private function getOffers(): array
	{
		/* получаем записи из БД */
		$list = $this->getItems();
//		JExit(b0dd($list));
		if (!$list) {
			$this->logs[] = 'Ошибка запроса к базе данных';
			return [];
		}
		
		/* цикл по записям БД */
		foreach ($list as $item) {
			$offers[] = new FeedOffer($item, FeedConfigCategories::FEED_CATEGORIES);
		}
		return $offers;
	}
	
	private function getItems()
	{
		/* получаем записи из БД */
		$db    = JFactory::getDbo();
		$query = "SELECT id, title, section_id, meta_descr, alias, categories, fields FROM #__js_res_record
                    WHERE (
                        section_id = '$this->sectionId' AND
                        id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id='$this->fieldModel' AND field_value='$this->modelName')) AND
                        id IN (SELECT record_id FROM #__js_res_record_values WHERE (field_id='$this->fieldAvailability' AND field_value=-1)) AND
                        published = 1
                    )";
		if ($this->limit > 0) {
			$query.= " LIMIT 0,{$this->limit}";
		}
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	/*
	 * Выводит лист офферов
	 */
	public function render(): bool
	{
        $handle = fopen(JPATH_ROOT . $this->config['filePath'], 'w+b');
		if ($handle === false) {
			$logs[] = 'Ошибка открытия файла ' . $this->config['filePath'];
			return false;
		}

		// Выводим шапку-заголовок
		$res = fwrite($handle, $this->getTitle());
		if ($res === false) {
			$logs[] = 'Ошибка записи в файл ' . $this->config['filePath'];
			fclose($handle);
			return false;
		}

		// Выводим Офферы
		foreach ($this->offers as $offer) {
			$content = $offer->renderOffer();
			$res     = fwrite($handle, $content);
			if ($res === false) {
				$logs[] = 'Ошибка записи в файл ' . $this->config['filePath'];
				fclose($handle);
				return false;
			}
		}

		// Выводим Footer
		$res = fwrite($handle, $this->getFooter());
		if ($res === false) {
			$logs[] = 'Ошибка записи в файл ' . $this->config['filePath'];
			fclose($handle);
			return false;
		}

		fclose($handle);
		$logs[] = 'Файл yml сформирован';
		return true;
	}

	private function getTitle(): string
	{
		$title =
			'<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
			'<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n" .
			'<yml_catalog date="' . date('Y-m-d H:i') . '">' . "\n" .
			'<shop>' . "\n" .
			'<name>' . FeedConfig::FEED_NAME . '</name>' . "\n" .
			'<company>' . FeedConfig::FEED_COMPANY . '</company>' . "\n" .
			'<url>' . FeedConfig::FEED_URL . '</url>' . "\n" .
			'<currencies>' . "\n" .
			'<currency id="' . FeedConfig::FEED_CURRENCY . '" rate="' . FeedConfig::FEED_CURRENCY_RATE . '"/>' . "\n" .
			'</currencies>' . "\n" .
			'<categories>' . "\n";
		
		foreach (FeedConfigCategories::FEED_CATEGORIES as $id => $category) {
			if ($category['name']) {
				$string = '<category id="' . $id . '"';
				if (isset($category['parent'])){
					$string .= ' parentId="' . $category['parent'] . '"';
				}
				$string .= '>' . $category['name'] . '</category>' . "\n";
				$title  .= $string;
			}
		}
		$title .= '</categories>' . "\n" .
			'<delivery-options>' . "\n";
		foreach (FeedConfig::FEED_DELIVERY_OPTIONS as $option) {
			$title .= '<option cost="' . $option['cost'] . '" days="' . $option['days'] . '"/>' . "\n";
		}
		$title .= '</delivery-options>' . "\n" .
			'<offers>' . "\n";
		
		return $title;
	}
	
	private function getFooter(): string
	{
		$footer = "</offers>\n" . "</shop>\n" . '</yml_catalog>';
		return $footer;
	}
}
