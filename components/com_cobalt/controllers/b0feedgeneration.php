<?php
/**
 * /index.php?option=com_cobalt&task=b0feed.create
 * /usr/bin/wget -O - -q /dev/null "https://stovesta.ru/index.php?option=com_cobalt&task=b0feedgeneration.create"
 */

defined('_JEXEC') or die();

JImport('b0.FeedGenerator.FeedGeneratorConfig');
JImport('b0.FeedGenerator.FeedGenerationManager');
JImport('b0.FeedGenerator.FeedGeneratorFactory');

class CobaltControllerB0FeedGeneration extends JControllerAdmin
{
    private FeedGenerationManager $feedManager;

    public function __construct()
    {
        parent::__construct();
        $feedGeneratorFactory = new FeedGeneratorFactory();
        $this->feedManager = new FeedGenerationManager($feedGeneratorFactory);
    }

    /**
     * Генерация всех фидов
     * /index.php?option=com_cobalt&task=b0feedgeneration.generateAll
     */
    public function generateAll(): void
    {
        $results = $this->feedManager->generateAllFeeds();
        $success = true;
        $errors = [];

        foreach ($results as $feedName => $result) {
            if (!$result['success']) {
                $success = false;
                $errors[] = "$feedName: {$result['error']}";
            }
        }

        if ($success) {
            $message = '200: Все файлы успешно сформированы';
        } else {
            $message = '500: Ошибка при формировании файлов: ' . implode(', ', $errors);
        }

        $this->sendMail($message);
        JExit($message);    }

    /**
     * Генерация одного конкретного фида
     * /index.php?option=com_cobalt&task=b0feedgeneration.generateSingle&feed_name=accesories-vesta
     */
    public function generateSingle(): void
    {
        $feedName = $this->input->getString('feed_name', '');

        if (empty($feedName)) {
            JExit('400: Не указано название фида');
        }

        $result = $this->feedManager->generateSingleFeed($feedName);

        if ($result['success']) {
            $message = "200: Файл '{$feedName}' успешно сформирован";
        } else {
            $message = "500: Ошибка при формировании файла '{$feedName}': " . $result['error'];
        }

        $this->sendMail($message);
        JExit($message);    }

    private function sendMail($messageBody): bool
    {
        return JFactory::getMailer()->sendMail(
            FeedGeneratorConfig::FEED_EMAIL_FROM,
            FeedGeneratorConfig::FEED_EMAIL_FROM_NAME,
            FeedGeneratorConfig::FEED_EMAIL_RECIPIENT,
            FeedGeneratorConfig::FEED_EMAIL_SUBJECT,
            $messageBody,
            true
        );
    }
}
