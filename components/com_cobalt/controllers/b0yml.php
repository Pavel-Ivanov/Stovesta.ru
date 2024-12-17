<?php
defined('_JEXEC') or die();

JImport('b0.Yml.Yml');
JImport('b0.Yml.YmlConfig');
//JImport('b0.fixtures');

/**
 * Class CobaltControllerB0Yml
 *
 * /index.php?option=com_cobalt&task=b0yml.create&mode=full - для Яндекс Товары-и-Цены и Турбо-страницы
 * /usr/bin/wget -O - -q /dev/null "https://stovesta.ru/index.php?option=com_cobalt&task=b0yml.create"
 */
class CobaltControllerB0Yml extends JControllerAdmin
{
	public $yml;
	
	public function create(): void
	{
		if (!isset($_GET['mode'])) {
			$mode = 'full';
		}
		else {
			$mode = 'full';
		}

		$this->yml = new Yml($mode);

		if ($this->yml->render()) {
			$message = '200: Файл Yml '. $mode .' сформирован';
			$this->sendMail($message);
			JExit($message);
		}
		else {
			$message = '200: Файл Yml '. $mode .' не сформирован';
			$this->sendMail($message);
			JExit($message);
		}
	}
	
	private function sendMail($messageBody): bool
	{
		return JFactory::getMailer()->sendMail(
			YmlConfig::YML_EMAIL_FROM, YmlConfig::YML_EMAIL_FROM_NAME,
			YmlConfig::YML_EMAIL_RECIPIENT, YmlConfig::YML_EMAIL_SUBJECT, $messageBody, TRUE
		);
	}
}
