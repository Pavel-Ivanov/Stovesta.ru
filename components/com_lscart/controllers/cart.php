<?php

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller;

defined('_JEXEC') or die();

JImport('b0.Cart.CartConfig');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.fixtures');

class LscartControllerCart extends JControllerLegacy
{
	private string $log_file_path = '/logs/b0_cart_1c.txt';
	private $log_file_handle;
	private array $logs = [];
	
	// Разрешать отправку Email при успешном результате
	private bool $enableSuccessEmail = CartConfig::CART_ENABLE_SUCCESS_EMAIL;
	// Разрешать запись лога при успешном результате
	private bool $enableSuccessLogs = CartConfig::CART_ENABLE_SUCCESS_LOG;
	// Разрешать запись входного массива
	private bool $enableRequestLog = CartConfig::CART_ENABLE_REQUEST_LOG;
	
	public function add()
	{
		/** @var CMSApplication $app */
		$app              = JFactory::getApplication();
		$input            = $app->input;
		$item_id          = $input->get('item_id', 1);
		$item_quantity    = $input->get('item_quantity', 1);
		$item             = unserialize($input->get('item', '', 'string'));
		$item['quantity'] = $item_quantity;
		
		// получить значение из сессии
		$cart = $app->getUserState('cart');
		// записать значение в сессию
		$cart[$item_id] = $item;
		// сохранить сессию
		$app->setUserState('cart', $cart);
//		jexit(json_encode($cart, JSON_THROW_ON_ERROR));
		jexit('200: Успешное добавление записи');
	}
	
	public function delete()
	{
		$app = JFactory::getApplication();
		$cart = $app->getUserState('cart');
		$item_id = $app->input->get('item_id', 1);
		jexit($item_id);
		unset ($cart[$item_id]);
		$app->setUserState('cart', $cart);
		jexit('200: Успешное удаление записи');
	}
	
	public function deleteAll(): void
	{
		/** @var CMSApplication $app */
		$app = JFactory::getApplication();
		$app->setUserState('cart', null);
		//b0debug($app->getUserState('cart'));
		jexit('200: Успешное удаление всех записей');
	}
	
	public function save(): void
	{
		/** @var CMSApplication $app */
		$app = Factory::getApplication();
		$siteName = JFactory::getConfig()->get('sitename');
		$inputData = json_decode($app->input->json->getRaw(), true, 512, JSON_THROW_ON_ERROR);
		/*		array(8) {
				["shop"]=> string(10) "planernaya"
				["customer"]=> array(4) {
					["name"]=> string(48) "Павел Владимирович Иванов"
					["surname"]=> string(48) "Павел Владимирович Иванов"
					["phone"]=> string(14) "(911)117-66-47"
					["email"]=> string(22) "p.ivanov.spb@gmail.com"
				}
				["items"]=> array(1) {
					[0]=> array(13) {
						["id"]=> string(4) "4610"
						*["title"]=> string(79) "Болт колеса Vesta, SW, Cross, XRay (1.6 и 1.8) с 2015- Белзан"
						["subTitle"]=> string(17) "М12х1,5х50х24"
						["url"]=> string(77) "/spareparts/item/4610-bolt-kolesa-vesta-sw-cross-xray-1-6-i-1-8-s-2015-belzan"
						["image"]=> string(73) "images/spareparts/2021-03/1615882873_a1493cc81e2cc4b81843a1556f5c91aa.jpg"
						*["productCode"]=> string(5) "54271"
						["isSpecial"]=> bool(false)
						["priceGeneral"]=> int(60)
						["priceDelivery"]=> int(54)
						["priceSpecial"]=> int(0)
						*["quantity"]=> int(1)
						*["priceCurrent"]=> int(60)
						*["amountCurrent"]=> int(60)
					}
				  }
				}*/
		
		$shop = $inputData['shop'];
		$name = $inputData['customer']['name'];
		$surname = $inputData['customer']['surname'];
		$phone = $inputData['customer']['phone'];
		$email = $inputData['customer']['email'];
		
		$emailParamsSystem = [
			'from'      => CartConfig::CART_EMAIL_FROM,
			'fromName'  => CartConfig::CART_EMAIL_FROM_NAME,
			'recipient' => CartConfig::CART_EMAIL_RECIPIENT,
			'subject'   => CartConfig::CART_EMAIL_SUBJECT_PREFIX_MYSELF . $siteName,
//			'body'      => ''
		];
		
/*		$emailParamsShop = [
			'from'      => CartConfig::CART_EMAIL_FROM,
			'fromName'  => CartConfig::CART_EMAIL_FROM_NAME,
			'recipient' => CartConfig::CART_EMAIL_RECIPIENT_SHOP,
			'subject'   => CartConfig::CART_EMAIL_SUBJECT_PREFIX_MYSELF . $siteName,
			'body'      => ''
		];*/
		
		$emailParamsUser = [
			'from'      => CartConfig::CART_EMAIL_FROM,
			'fromName'  => CartConfig::CART_EMAIL_FROM_NAME,
			'recipient' => $email,
			'subject'   => CartConfig::CART_EMAIL_SUBJECT_PREFIX . $siteName,
//			'body'      => ''
		];
		
//		$cart = $app->getUserState('cart');
		/*		if (!$cart) {
					return;
				}*/
//		$cart_keys = implode(',', array_keys($cart));
		$itemsKeys = [];
		foreach ($inputData['items'] as $item) {
			$itemsKeys[] = $item['id'];
		}
		$itemsKeys = implode(',', $itemsKeys);
		
		// Получаем данные по наличию
		/** @var JDatabaseDriver $db */
		$db    = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('id, title, type_id, alias, fields');
		$query->from('#__js_res_record');
		$query->where('id IN ('.$itemsKeys.')');
		$query->order('title');
		$db->setQuery($query);
		$goods = $db->loadObjectList();
		
		$availability = [];
		foreach ($goods as $item) {
			$fields = json_decode($item->fields, true, 512, JSON_THROW_ON_ERROR);
			$availability[$item->id]['sedova'] = $item->type_id == SparepartIds::ID_TYPE ? $fields[SparepartIds::ID_SEDOVA] : $fields[AccessoryIds::ID_SEDOVA];
			$availability[$item->id]['khimikov'] = $item->type_id == SparepartIds::ID_TYPE ? $fields[SparepartIds::ID_KHIMIKOV] : $fields[AccessoryIds::ID_KHIMIKOV];
			$availability[$item->id]['zhukova'] = $item->type_id == SparepartIds::ID_TYPE ? $fields[SparepartIds::ID_ZHUKOVA] : $fields[AccessoryIds::ID_ZHUKOVA];
			$availability[$item->id]['kultury'] = $item->type_id == SparepartIds::ID_TYPE ? $fields[SparepartIds::ID_KULTURY] : $fields[AccessoryIds::ID_KULTURY];
			$availability[$item->id]['planernaya'] = $item->type_id == SparepartIds::ID_TYPE ? $fields[SparepartIds::ID_PLANERNAYA] : $fields[AccessoryIds::ID_PLANERNAYA];
		}
		
		// Формируем номер Заказа
		$db->setQuery('SELECT max(id) FROM #__lscart_orders');
		$lastId = $db->loadResult()+1;
		$orderNumber = 'Веста-' .date('Y-m-d') . '-' . $lastId;
		
		// Формируем массив данных для записи в БД
		$data = [];
		$data['shop'] = $shop;
		$dataCart = [];
		foreach ($inputData['items'] as $item) {
			$dataCart[$item['id']]['title'] = $item['title'];
			$dataCart[$item['id']]['code'] = $item['productCode'];
			$dataCart[$item['id']]['price'] = $item['priceCurrent'];
			$dataCart[$item['id']]['quantity'] = $item['quantity'];
			$dataCart[$item['id']]['amount'] = $item['amountCurrent'];
		}
		$data['cart'] = $dataCart;
		
		// Сохраняем запись в БД
		$data_db = [
			'order_id' => $orderNumber,
			'destination' => 'myself',
			'name' => $name . ' ' . $surname,
			'phone' => '+7'.trim($phone),
			'email' => $email,
			'data' => json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512),
			'created' => JHtml::date('now', 'Y-m-d H:i:s'),
			'in_sync' => 0,
			'synchronized' => ''
		];
//		jexit(b0debug($availability));
		JTable::addIncludePath(JPATH_COMPONENT.'/tables/');
		$row = JTable::getInstance('order', 'Table');
		$row->reset();
		
		if (!$row->check()) {
			exit();
		}
		if (!$row->bind($data_db)){
			exit();
		}
		if (!$row->store()){
			exit();
		}
		
		$smsParamsSystem = [
			'text' => 'Заказ ' . $orderNumber . ' / '. $name . ' / +' . trim(str_replace(['-', '(', ')'],'', $phone)),
			'number' => '79111998979'
		];
		
		$smsParamsUser = [
			'text' => 'Ваш заказ ' . $orderNumber . ' принят, ожидайте звонка',
			'number' => trim(str_replace(['-', '(', ')'],'', $phone))
		];
		
		// Формируем текст письма
		$emailBody = '';
		$emailBodyCustomer = '';
		if ($lastId) {
			$emailBody .= 'Номер заказа: '. $orderNumber . '<br>';
			$emailBodyCustomer .= 'Номер Заказа: '. $orderNumber . '<br>';
		}
		
		$emailBody .= 'Контактное лицо: '. $name . ' ' . $surname . '<br>';
		$emailBodyCustomer .= 'Контактное лицо: '. $name . '<br>';
		
		$emailBody .= 'Контактный телефон: '. $phone . '<br>';
		$emailBodyCustomer .= 'Контактный телефон: '. $phone . '<br>';
		
		$emailBody .= 'Email: '. $email . '<br>';
		$emailBodyCustomer .= 'Email: '. $email . '<br>';
		
		$emailBody .= '<br>Тип заказа: Самовывоз<br>';
		switch ($shop) {
            case CartConfig::CART_ID_GAGARINA:
                $emailParamsSystem['recipient'] = CartConfig::CART_EMAIL_RECIPIENT_SHOP[CartConfig::CART_ID_GAGARINA];
                $emailBody .= 'Магазин: ул. Кузнецовская, 52 к.13<br>';
                break;
            case CartConfig::CART_ID_KHIMIKOV:
                $emailParamsSystem['recipient'] = CartConfig::CART_EMAIL_RECIPIENT_SHOP[CartConfig::CART_ID_KHIMIKOV];
                $emailBody .= 'Магазин: Кудровский пр-д, 5<br>';
                break;
            case CartConfig::CART_ID_KULTURY:
                $emailParamsSystem['recipient'] = CartConfig::CART_EMAIL_RECIPIENT_SHOP[CartConfig::CART_ID_KULTURY];
                $emailBody .= 'Магазин: 1-й Верхний пер., 10<br>';
                break;
            case CartConfig::CART_ID_ZHUKOVA:
                $emailParamsSystem['recipient'] = CartConfig::CART_EMAIL_RECIPIENT_SHOP[CartConfig::CART_ID_ZHUKOVA];
                $emailBody .= 'Магазин: ул. Портовая, 15-Б<br>';
                break;
            case CartConfig::CART_ID_PLANERNAYA:
                $emailParamsSystem['recipient'] = CartConfig::CART_EMAIL_RECIPIENT_SHOP[CartConfig::CART_ID_PLANERNAYA];
                $emailBody .= 'Магазин: ул. Планерная, 15-Б<br>';
                break;
		}
		$emailBody .= '<br><strong>Заказанные товары: </strong><br>';
		$emailBodyCustomer .= '<br><strong>Заказанные товары: </strong><br>';
		
		$totalPrice = 0;
		foreach ($dataCart as $id=>$item) {
			$totalPrice += $item['amount'];
			
			$emailBody .= $item['code'] . ' / ' . $item['title'] . ' - <b>' .
				$item['quantity'] . '</b> шт. * '.$item['price'].
				' руб. = '.$item['amount'].' руб.<br>';
			$emailBodyCustomer .= $item['code'] . ' / ' . $item['title'] . ' - <b>' .
				$item['quantity'] . '</b> шт. * '.$item['price'].
				' руб. = '.$item['amount'].' руб.<br>';
			$emailBody .= '     *** наличие: большевиков- <b>'.$availability[$id]['khimikov'].
				'</b> / гагарина- <b>'.$availability[$id]['sedova'].
				'</b> / жукова- <b>'.$availability[$id]['zhukova'].
				'</b> / культуры- <b>'.$availability[$id]['kultury'].
				'</b> / планерная- <b>'.$availability[$id]['planernaya'].'</b><br>';
			$emailBody .= '<br>';
		}
		$emailBody .= 'Общая  сумма: ' . $totalPrice . ' руб.';
		$emailBodyCustomer .= 'Общая  сумма: ' . $totalPrice . ' руб.';
		
		$emailParamsSystem['body'] = $emailBody;
		$this->sendMail($emailParamsSystem);
		
		$emailParamsUser['body'] = $emailBodyCustomer;
		$this->sendMail($emailParamsUser);
		
		$this->sendSMS($smsParamsSystem);
		$this->sendSMS($smsParamsUser);
		
		//TODO сделать проверку на отправку Email
		$app->setUserState('cart', '');
		
		jexit('200: Успешное сохранение заказа');
	}
	
	/**
	 * Возвращает новые Заказы
	 */
	public function sync_1c(): void
	{
		// Получить все записи с in_sync = 0
		/** @var JDatabaseDriver $db */
		$db    = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__lscart_orders');
		$query->where('in_sync=0');
		$db->setQuery($query);
		$orders = $db->loadObjectList();
		
		// Цикл по Заказам
		$response = [];
		foreach ($orders as $order) {
			$data = json_decode($order->data, true, 512, JSON_THROW_ON_ERROR);
			$response[$order->order_id]['created'] = trim(str_replace(['-', '(', ')', ' ', ':'],'', $order->created));
			$response[$order->order_id]['phone'] = $order->phone;
			$response[$order->order_id]['destination'] = 'myself';
			$response[$order->order_id]['shop'] = $data['shop'];
			$total = 0;
			$prods = [];
			foreach ($data['cart'] as $id => $product) {
				$prods[] = [
					'id' => $product['code'],
					'quantity' => $product['quantity'],
					'price' => $product['price'],
				];
				$total += $product['quantity'] * $product['price'];
			}
			$response[$order->order_id]['products'] = $prods;
			$response[$order->order_id]['total'] = $total;
		}
		// Отправить ответ
		jexit(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
	}
	
	/**
	 * Устанавливает флаг заказа- получен
	 */
	public function clear_1c(): void
	{
		$emailParamsSystem = [
			'from'      => CartConfig::CART_EMAIL_FROM,
			'fromName'  => CartConfig::CART_EMAIL_FROM_NAME,
			'recipient' => CartConfig::CART_EMAIL_RECIPIENT,
			'subject'   => CartConfig::CART_EMAIL_SUBJECT_PREFIX . 	JFactory::getConfig()->get('sitename'),
//			'body'      => ''
		];
		
		$input = JFactory::getApplication()->input;
		$orderId = $input->getString('id', '');
		if ($orderId === ''){
			$this->logs[] = 'Пустое тело входного запроса';
			$emailParamsSystem['body'] = 'Пустое тело входного запроса';
			$this->writeLogs();
			$this->sendMail($emailParamsSystem);
			jexit('500: Пустое тело входного запроса');
		}
		
		/** @var JDatabaseDriver $db */
		$db    = JFactory::getDbo();
		$query = "UPDATE #__lscart_orders SET in_sync=1, synchronized='" . date('Y-m-d H:i:s') . "' WHERE order_id=" . $orderId;
		$db->setQuery($query);
		if ($db->execute()) {
			$this->logs[] = 'Успешное обновление- ' . $orderId;
			$emailParamsSystem['body'] = 'Успешное обновление- ' . $orderId;
		}
		else {
			$this->logs[] = 'Неуспешное обновление- ' . $orderId;
			$emailParamsSystem['body'] = 'Неуспешное обновление- ' . $orderId;
			$this->writeLogs();
			$this->sendMail($emailParamsSystem);
			jexit('500: Неуспешное обновление');
		}
		
		// Запись логов
		if ($this->enableSuccessLogs) {
			$this->writeLogs();
		}
		
		// Отправка почты
		if ($this->enableSuccessEmail) {
			$this->sendMail($emailParamsSystem);
		}
		
		jexit('200: Успешное обновление- ' . $orderId);
	}
	
	public function changeQuantity(): void
	{
		/** @var CMSApplication $app */
		$app = JFactory::getApplication();
		$input = $app->input;
		$item_id = $input->get('item_id', 1);
		$item_quantity = $input->get('item_quantity', 1);
		$cart = $app->getUserState('cart');
		$cart[$item_id] = ['quantity' => $item_quantity];
		$app->setUserState('cart', $cart);
	}
	
	private function sendMail(array $params): void
	{
		JFactory::getMailer()->sendMail($params['from'], $params['fromName'], $params['recipient'], $params['subject'], $params['body'], true);
	}
	
	private function sendSMS(array $params)
	{
		$query = 'https://gate.sms-manager.ru/_getsmsd.php?user=logan&password=753636&sender=StoVesta&SMSText=' .$params['text']. '&GSM=' .$params['number'];
		return file_get_contents($query);
	}
	
	private function writeLogs(): void
	{
		$this->log_file_handle = fopen(JPATH_ROOT . $this->log_file_path, 'ab+');
		if ($this->log_file_handle === false) {
			die('500: Ошибка открытия файла лога');
		}
		
		fwrite($this->log_file_handle, '===== ' . date_format(new DateTime(), 'Y-m-d H:i:s') . ' =====' . "\n");
		
		foreach ($this->logs as $log) {
			fwrite($this->log_file_handle, $log . "\n");
		}
		fclose($this->log_file_handle);
	}
	
}
