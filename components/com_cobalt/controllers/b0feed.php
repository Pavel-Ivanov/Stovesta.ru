<?php
defined('_JEXEC') or die();

JImport('b0.Feed.Feed');
JImport('b0.Feed.FeedConfig');
JImport('b0.Feed.FeedConfigFeeds');
JImport('b0.fixtures');

/**
 * /index.php?option=com_cobalt&task=b0feed.create
 * /usr/bin/wget -O - -q /dev/null "https://stovesta.ru/index.php?option=com_cobalt&task=b0feed.create"
 */
class CobaltControllerB0Feed extends JControllerAdmin
{
	public Feed $feed;
	
	public function create(): void
	{
		foreach (FeedConfigFeeds::FEED_CONFIG_FEEDS as $config) {
			if ($config['isNeed']) {
				$this->feed = new Feed($config);
			}
			else {
				continue;
			}
			
			if ($this->feed->render()) {
				$message = '200: Файл '. $config['name'] .' сформирован';
			}
			else {
				$message = '200: Файл '. $config['name'] .' не сформирован';
                $this->sendMail($message);
            }
		}
        $this->sendMail('200: Файлы сформированы');
		JExit('200: Файлы сформированы');
	}

    public function createSingleFeed()
    {
        // Получаем название позиции из запроса
        $feedName = $this->input->getString('feed_name', '');

        // Проверяем, существует ли такая позиция в конфигурации
        if (!array_key_exists($feedName, FeedConfigFeeds::FEED_CONFIG_FEEDS)) {
            $this->setMessage('Фид не найден', 'error');
            return false;
        }

        // Получаем конфигурацию для данной позиции
        $feedConfig = FeedConfigFeeds::FEED_CONFIG_FEEDS[$feedName];
//        JExit(b0debug($feedConfig));
        // Если фид не требуется создавать, прекращаем выполнение
        if (!$feedConfig['isNeed']) {
            $this->sendMail('200: Фид не требуется создавать');
            JExit('200: Фид не требуется создавать');
        }

        // Создаем экземпляр класса Feed
        $this->feed = new Feed($feedConfig);
        //JExit(b0debug($this->feed));
        if ($this->feed->render()) {
            $message = '200: Файл '. $feedConfig['name'] .' сформирован';
        }
        else {
            $message = '200: Файл '. $feedConfig['name'] .' не сформирован';
        }
        $this->sendMail($message);
        JExit($message);

    }
	
	private function sendMail($messageBody): bool
	{
		return JFactory::getMailer()->sendMail(
			FeedConfig::FEED_EMAIL_FROM, FeedConfig::FEED_EMAIL_FROM_NAME,
			FeedConfig::FEED_EMAIL_RECIPIENT, FeedConfig::FEED_EMAIL_SUBJECT, $messageBody, TRUE
		);
	}
}
