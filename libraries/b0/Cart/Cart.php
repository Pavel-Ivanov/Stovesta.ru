<?php
defined('_JEXEC') or die();

//JImport('b0.Cart.Cart');
JImport('b0.Cart.CartItem');
JImport('b0.Cart.CartConfig');
//JImport('b0.fixtures');

class Cart
{
//	protected const ICON_RUB = '<i class="uk-icon-rub uk-text-muted"></i>';

//	public const LIMIT_DELIVERY_CITY = 500;
//	public const PRICE_DELIVERY_CITY = 300;
//	public const LIMIT_DELIVERY_SATELLITES = 1500;
//	public const PRICE_DELIVERY_SATELLITES = 500;

	public $goods;
	public $summTotal;
	public $priceDeliveryCity;
	public $deltaDeliveryCity;
	public $priceDeliverySatellites;
	public $deltaDeliverySatellites;

	// Данные клиента
	public $userId;
	public $userName;
	public $userPhone;
	public $userEmail;

	public function __construct($goods = null)
	{
		if (!$goods) {
			$cart = JFactory::getApplication()->getUserState('cart');
			if (!$cart) {
				return;
			}
			$cart_keys = implode(',', array_keys($cart));

			$db = JFactory::getDbo();
			$query = $db->getQuery(TRUE);
			$query->select('id, title, alias, type_id, fields');
			$query->from('#__js_res_record');
			$query->where('id IN ('.$cart_keys.')');
			$query->order('title');
			$db->setQuery($query);
			$goods = $db->loadObjectList();
		}

//		$componentParameters = JComponentHelper::getParams('com_cobalt');

		foreach ($goods as $item) {
//			$this->goods[$item->id] = new CartItem($item);
			$this->goods[] = new CartItem($item);
		}
		$this->setSummTotal();
		$this->setPriceDeliveryCity();
		$this->setDeltaDeliveryCity();
		$this->setPriceDeliverySatellites();
		$this->setDeltaDeliverySatellites();
	}

	public function addItem($id, $item)
	{
		$this->goods[$id] = $item;
	}

	public function setSummTotal()
	{
		$summ = 0;
		foreach ($this->goods as $key => $item) {
			$summ += $item->priceDelivery * $item->quantity;
		}
		$this->summTotal = $summ;
	}

	public function setPriceDeliveryCity()
	{
		$this->summTotal < CartConfig::LIMIT_DELIVERY_CITY ? $this->priceDeliveryCity = CartConfig::PRICE_DELIVERY_CITY : $this->priceDeliveryCity = 0;
	}

	public function setDeltaDeliveryCity()
	{
		$this->deltaDeliveryCity = CartConfig::LIMIT_DELIVERY_CITY - $this->summTotal;
	}

	public function setPriceDeliverySatellites()
	{
		$this->summTotal < CartConfig::LIMIT_DELIVERY_SATELLITES ? $this->priceDeliverySatellites = CartConfig::PRICE_DELIVERY_SATELLITES : $this->priceDeliverySatellites = 0;
	}

	public function setDeltaDeliverySatellites()
	{
		$this->deltaDeliverySatellites = CartConfig::LIMIT_DELIVERY_SATELLITES - $this->summTotal;
	}

	public function renderPrice($price) {
		echo number_format($price, 0, '.', ' ') . ' ' . CartConfig::ICON_RUB;
	}

	public function getSummTotal()
	{
		return $this->summTotal;
	}

	public function store()
	{
		$data_db = [
			'user_id' => $this->userId,
			'name' => $this->userName,
			'phone' => $this->userPhone,
			'email' => $this->userEmail,
			'created_date' => JHtml::date('now', 'Y-m-d H:i:s'),
			'status' => 'confirm',
			'data' => json_encode($this->goods, JSON_UNESCAPED_UNICODE)
		];

		JTable::addIncludePath(JPATH_COMPONENT.'/tables/');
		$row = JTable::getInstance('order', 'Table');
		$row->reset();

		if (!$row->check()) {
			//echo "<script> alert('Ошибка проверки перед записью в БД'); </script>\n";
			exit();
		}

		if (!$row->bind($data_db)){
			//echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if (!$row->store()){
			//echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
	}

	public function sendEmail()
	{
		$emailBody = '';
		if ($this->userName) {
			$emailBody .= 'Контактное лицо: '. $this->userName . '<br>';
		}
		$emailBody .= 'Контактный телефон: +7-'. $this->userPhone . '<br>';
		$emailBody .= '<br><strong>Заказанные товары: </strong><br>';

		foreach ($this->goods as $key => $item) {
			$fields = json_decode($item->fields, TRUE);
			$cart[$item->id]['id'] = $item->id;
			$cart[$item->id]['title'] = $item->title;
			$cart[$item->id]['code'] = ($item->type_id == 1) ? $fields[4] : $fields[74];
			$cart[$item->id]['price'] = ($item->type_id == 1) ? $fields[65] : $fields[81];

			$emailBody .= $item['code'] . ' / ' . $item['title'] . ' - ' . $item['quantity'] . ' шт. * '.$item['price'].' руб.<br>';
		}
		$emailBody .= 'Общая  сумма: ' . $this->summTotal . ' руб.';


		$email_params_system = [
			'from'      => 'admin@stovesta.ru',
			'fromName'  => 'StoVesta',
			'recipient' => ['shop@stovesta.ru', 'ananasio.lu@gmail.com', 'p.ivanov@stovesta.ru'],
			'subject'   => 'Заказ на запчасти StoVesta',
			'body'      => $emailBody
		];
		$this->_sendMail($email_params_system);

		if (isset($email)) {
			$email_params_user = array(
				'from'      => 'shop@stovesta.ru',
				'fromName'  => 'StoVesta',
				'recipient' => $email,
				'subject'   => 'Заказ на запчасти StoVesta',
				'body'      => $emailBody
			);
			$this->_sendMail($email_params_user);
		}
	}

	public function setUserId($id)
	{
		$this->userId = $id;
	}

	public function setUserName($name)
	{
		$this->userName = $name;
	}

	public function setUserPhone($phone)
	{
		$this->userPhone = $phone;
	}

	public function setUserEmail($email)
	{
		$this->userEmail = $email;
	}

	private function _sendMail($params) {
		$result = JFactory::getMailer()->sendMail($params['from'], $params['fromName'], $params['recipient'], $params['subject'], $params['body'], TRUE);
		return $result;
	}

	private function _sendSMS($params) {
		$query = "http://gate.sms-manager.ru/_getsmsd.php?user=logan&password=753636&sender=StoVesta&SMSText=".$params['text']."&GSM=".$params['number'];
		$result = file_get_contents($query);
		return $result;
	}


}