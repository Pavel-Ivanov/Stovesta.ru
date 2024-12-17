<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

class ContactusControllerAdd extends ContactusController
{
	
	public function save()
	{	
		$url = JFactory::getURI();
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$method = $input->getMethod();
		
		$data = array();
		$data = $app->input->post->getArray($_POST);

		$mod = 	($data['module_id']!==null) ? $data['module_id'] : 0;
		$model= $this->getModel('add');
		$params = $model->getParams($data['module_id']);
		$url_redirect = (!empty($params->redirect_page)) ? $params->redirect_page : JFactory::getURI();

		if ($data['module_hash'] == JUserHelper::getCryptedPassword($url->toString()))
		{
			//Check captcha errors
			if ($params->captcha == 1)
			{
				$url_c = 'https://www.google.com/recaptcha/api/siteverify';
				$data_c = array('secret' => $params->captcha_secretkey, 'remoteip' => $data['ip'], 'response' => $data['g-recaptcha-response']);

				$options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data_c),
					),
				);
				$context  = stream_context_create($options);
				$result_c = json_decode(file_get_contents($url_c, false, $context));

				if (!empty($params->captcha_secretkey))
				{
					if (isset($result_c->success) && ($result_c->success == 1))
					{
						$res = 11;
					} else
					{
						unset($data["email"]);
						$app->setUserState('contactus.add.form.data', $data);
						setcookie('contactus_captcha', null, -1,'/',null);
						setcookie('sending-alert',$mod,time()+60,'/',null);
						setcookie('alert-type',"captcha",time()+60,'/',null);
						$app->redirect(JRoute::_($url, false));
					}
				}
			}
			//Check added files errors
			if (file_exists($_FILES["file"]["tmp_name"][0])){
			
				for ($i = 0; $i < count($_FILES["file"]["name"]);$i++ ){
					
					if ($_FILES["file"]["tmp_name"][$i] !=='')
					{
						if ($_FILES["file"]["error"][$i] > 0) { 
							unset($data["email"]);
							$app->setUserState('contactus.add.form.data', $data);
							setcookie('contactus_captcha', null, -1,'/',null);
							$app->redirect(JRoute::_($url, false), $_FILES["files"]["error"][$i]);
							setcookie('sending-alert',$mod,time()+60,'/',null);
							setcookie('alert-type',"file",time()+60,'/',null);
						}				
						if ($_FILES["file"]["size"][$i] > 10000000){
							unset($data["email"]);
							$app->setUserState('contactus.add.form.data', $data);
							setcookie('contactus_captcha', null, -1,'/',null);
							setcookie('sending-alert',$mod,time()+60,'/',null);
							setcookie('alert-type',"file",time()+60,'/',null);
							$app->redirect(JRoute::_($url, false));	
						}	
					}
				}
			}

			//Bind and store to db the form data
			$data["data"] = json_encode($data);
			JTable::addIncludePath(JPATH_COMPONENT.'/tables/');
			$row = JTable::getInstance('contactus', 'Table');	
			$data["created_at"] = JHtml::date('now', 'Y-m-d H:i:s');	
			
			if (!$row->bind($data)){
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}	
			if (!$row->store()){
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}

			//Call the model and send message
			$model->sendMessage($data, $params,$row->id,$url->getHost());

			setcookie('sending-alert',$mod,time()+60,'/',null);
			setcookie('alert-type',"success",time()+60,'/',null);
			$app-> redirect($url_redirect);
		} else 
		{
			setcookie('contactus_captcha', null, -1,'/',null);
			setcookie('sending-alert',$mod,time()+60,'/',null);
			setcookie('alert-type',"file",time()+60,'/',null);
			$app->redirect(JRoute::_($url, false));	
		}
	}
}
