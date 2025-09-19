<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class ContactusModelAdd extends JModelLegacy
{	
	
	function sendMessage($data,$params,$id, $host){
		
		$mailer = JFactory::getMailer();

		$config = JFactory::getConfig();
		$sender = array( 
		    $config->get( 'mailfrom' ),
		    $config->get( 'fromname' ) 
		);

		if (isset($params->field)) {
			foreach ($params->field as $field) {
				if ($field->type === "Email") {
					$mailer->addReplyTo($data[$field->name]);
				}
			}
		}

		$mailer->setSender($sender);
		$mail = $this->getRecipient($params->admin_mail);
		$mailer->addRecipient($mail);
		$subject = $this->getSubject($params->mail_subject_text);
		$mailer->setSubject($subject);
		$body = $this->getBody($data, $params);
		$mailer->setBody($body);
        $mailer->IsHTML(true);
        $mailer->Send();


        /*		if (file_exists($_FILES["file"]["tmp_name"][0])){
                    $attachments = array();
                    $name = array();
                    for ($i = 0; $i < count($_FILES["file"]["name"]);$i++ ){
                        if ($_FILES["file"]["tmp_name"][$i] !=='')
                        {
                            $attachments[] =  $_FILES["file"]["tmp_name"][$i];
                            $name[]= $_FILES["file"]["name"][$i];
                        }
                    }
                    $mailer->addAttachment($attachments, $name);
                }*/

        /*		if ((isset($params->sms_flag)) && ($params->sms_flag == 1)){
			//$smsText = $this->getSMStext($data, $params);
			$smsText = $params->sms_text.' '.$data['field1'].' '.$data['field2'];
			$smsNumber = $params->sms_self_number;
			$query = "http://gate.sms-manager.ru/_getsmsd.php?user=logan&password=753636&sender=StoVesta&SMSText=".$smsText."&GSM=".$smsNumber;

			$ch = curl_init("http://sms.ru/sms/send");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				"api_id"		=>	$params->sms_api_id,
				"to"			=>	$params->sms_self_number,
				"text"		=>	$sms_text,
				"partner_id" => "108497"
			));
			$bd = curl_exec($ch);
			curl_close($ch);
        }*/

//        b0debug($data);
//        b0dd($params);

        $call_value = $_COOKIE['_ct_session_id']; /* ID сессии Calltouch, полученный из cookie */
        $ct_site_id = '74310';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
        curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
        curl_setopt($ch, CURLOPT_POST, 1);
        // Initialize variables to store field names
        $name_field = '';
        $phone_field = '';
        $email_field = '';
        
        // Find field names based on titles
        if (isset($params->field)) {
            foreach ($params->field as $field) {
                if ($field->title === "Ваше имя") {
                    $name_field = $field->name;
                } elseif ($field->title === "Ваш телефон") {
                    $phone_field = $field->name;
                } elseif ($field->title === "Email") {
                    $email_field = $field->name;
                }
            }
        }
        
        // Get values from $data using field names
        $fio = isset($name_field) && isset($data[$name_field]) ? $data[$name_field] : '';
        $phone = isset($phone_field) && isset($data[$phone_field]) ? $data[$phone_field] : '';
        $email = isset($email_field) && isset($data[$email_field]) ? $data[$email_field] : '';
        $subject = isset($params->mail_subject_text) && isset($data[$params->mail_subject_text]) ? $data[$params->mail_subject_text] : '';
        
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "fio=".urlencode($fio)
            ."&phoneNumber=".$phone
            ."&email=".$email
            ."&subject=".$subject
            ."".($call_value !== 'undefined' ? "&sessionId=".$call_value : ""));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $calltouch = curl_exec ($ch);
        curl_close ($ch);

        /*
        {site_id} — ID Вашего сайта внутри ЛК Calltouch. Указывается без фигурных скобок. Его можно получить в разделе:  Интеграции /   Отправка данных во внешние системы / API и Webhooks / API / ID личного кабинета.
        mod_id — уникальный идентификатор скрипта Calltouch. Его можно получить в разделе:  Интеграции /   Отправка данных во внешние системы / API и Webhooks / API / ID счетчика.
        При использовании такого метода, следует обратить внимание на 3 пункта:

        ID сессии Calltouch, который необходимо отправить в качестве параметра sessionId API-запроса на создание заявки, необходимо передавать в PHP-обработчик с клиентской стороны, получая значение ID сессии из соответствующей переменной.
        Для корректной передачи кириллических символов в запросе и обхода проблем с кодировкой, необходимо применять PHP-функцию urlencode ко всем PHP-переменным, передаваемым в качестве входных параметров API-запроса. Т.е., если ФИО клиента находится в $_POST['name'], то в API-запрос ее надо добавить как urlencode($_POST['name']).
        В API-запросе в явном виде должна быть указана кодировка utf-8.
        Во входных параметрах fio, phoneNumber, email и subject скрипта выше указаны тестовые данные формы соответственно: $_POST['name'], $_POST['phone'], $_POST['email'] и значение "Заявка с сайта". При написании реального скрипта на сервере для отправки заявок в Calltouch, необходимо настроить передачу данных, введенных клиентом на отправляемой форме, в качестве значений соответствующих входных параметров API-запроса на создание заявки.
        */


    }

/*	function sendSMS($data,$params,$id, $host) {
		$query = "http://gate.sms-manager.ru/_getsmsd.php?user=logan&password=753636&sender=StoVesta&SMSText=".$params['text']."&GSM=".$params['number'];
		$result = file_get_contents($query);
		return $result;
	}*/


	function getParams($module_id){
		$query = $this->_db->getQuery(true);
		if ($module_id > 0) {
			$query->select('params')
			->from('#__modules')
			->where("module='mod_contactus' AND id={$module_id}");
		} else {
			$query->select('params')
			->from('#__modules')
			->where('module="mod_contactus"');
		}	
		$this->_db->setQuery($query);
		$array =  $this->_db->loadAssoc();
		$parameters =  json_decode($array['params']); 
		return $parameters;
	}

	function getRecipient($admin_mail){
		$mail = explode(",",$admin_mail);
		if (empty($mail[0])){
			$config = JFactory::getConfig();
			$mail = $config->get('mailfrom');
		}

		return $mail;
	}
	
	function getSubject($title){
		$subject = $title;
		if (empty($subject)){
			$subject = JText::_('COM_CONTACTUS_NEW_FEEDBACK');
		}
		return $subject;
	}
	
	function getSMStext($data, $params){
		if (empty($params->sms_text)){
			$sms_text = JText::_('COM_CONTACTUS_SMS_TEXT_DEFAULT');
		}
		else
			if (strpos($params->sms_text, '{') !== false) {
				foreach($data as $key => $value) {
				    $search[] = "{" . $key . "}";
				    $replace[] = $value;
				}
				$sms_text = str_replace($search, $replace, $params->sms_text);
			}
			else {
				$sms_text = $params->sms_text ;
			}
		return $sms_text;
	}

	function getBody($data, $params){

		if (isset($data["created_at"])){ 
			$body = '<br><b>'.JText::_('COM_CONTACTUS_CREATED_AT').'</b>: '.$data["created_at"];
		}	
		if (isset($params->field)) {
			foreach ($params->field as $field) {
				if ($field->type !== "Text") {
					$body = $body . '<br><b>'.$field->title.'</b>: '.$data[$field->name];	
				}
			}
		}
		if (!empty($data["module_title"])){ 
			$body = $body.'<br><b>'.JText::_('COM_CONTACTUS_FORM_ID').'</b>: '.$data['module_title'];
		}
		if (isset($data['page'])){ 
			$body = $body.'<br><b>'.JText::_('COM_CONTACTUS_PAGE').'</b>: '.$data['page'];
		}
		if (isset($data['ip'])){ 
			$body = $body.'<br><b>'.JText::_('COM_CONTACTUS_IP').'</b>: '.$data['ip'];
		}

		return $body;
	}
}
