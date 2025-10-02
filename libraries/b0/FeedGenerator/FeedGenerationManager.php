<?php
defined('_JEXEC') or die();

JImport('b0.FeedGenerator.FeedGeneratorConfig');
JImport('b0.FeedGenerator.FeedGeneratorFactory');

class FeedGenerationManager
{
    private FeedGeneratorFactory $factory;

    public function __construct(FeedGeneratorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Генерация всех фидов из определений
     * @return array<string, array{success: bool, error?: string}>
     */
    public function generateAllFeeds(): array
    {
        $definitions = $this->factory->getFeedDefinitions();
        $results = [];

        foreach ($definitions as $name => $def) {
            if (!($def['isNeed'] ?? false)) {
                $results[$name] = ['success' => true];
                continue;
            }
            $results[$name] = $this->generateSingleFeed($name);
        }

        return $results;
    }

    /**
     * Генерация одного фида (минимальная валидная YML-заглушка, без смешивания с legacy)
     * @param string $feedName
     * @return array{success: bool, error?: string}
     */
    public function generateSingleFeed(string $feedName): array
    {
        $definitions = $this->factory->getFeedDefinitions();
        if (!isset($definitions[$feedName])) {
            return ['success' => false, 'error' => 'Неизвестный фид'];
        }
        $def = $definitions[$feedName];
        if (!($def['isNeed'] ?? false)) {
            return ['success' => true];
        }

        $filePath = $def['filePath'];
        try {
            $this->factory->ensureDirectory($filePath);
            $full = JPATH_ROOT . $filePath;
            $handle = fopen($full, 'w+b');
            if ($handle === false) {
                return ['success' => false, 'error' => 'Ошибка открытия файла'];
            }

            // Минимальный валидный YML (пустой список офферов)
            $title = $this->buildTitle();
            $footer = $this->buildFooter();

            if (fwrite($handle, $title) === false) {
                fclose($handle);
                return ['success' => false, 'error' => 'Ошибка записи заголовка'];
            }

            // Здесь позже появится реальная генерация офферов нового пайплайна

            if (fwrite($handle, $footer) === false) {
                fclose($handle);
                return ['success' => false, 'error' => 'Ошибка записи футера'];
            }

            fclose($handle);
            return ['success' => true];
        } catch (Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function buildTitle(): string
    {
        $title =
            '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n" .
            '<yml_catalog date="' . date('Y-m-d H:i') . '">' . "\n" .
            '<shop>' . "\n" .
            '<name>' . FeedGeneratorConfig::FEED_NAME . '</name>' . "\n" .
            '<company>' . FeedGeneratorConfig::FEED_COMPANY . '</company>' . "\n" .
            '<url>' . FeedGeneratorConfig::FEED_URL . '</url>' . "\n" .
            '<currencies>' . "\n" .
            '<currency id="' . FeedGeneratorConfig::FEED_CURRENCY . '" rate="' . FeedGeneratorConfig::FEED_CURRENCY_RATE . '"/>' . "\n" .
            '</currencies>' . "\n" .
            '<categories>' . "\n" .
            // На старте без детализированных категорий; при развитии перенесём карту категорий
            '<category id="1">Авто</category>' . "\n" .
            '</categories>' . "\n" .
            '<delivery-options>' . "\n";
        foreach (FeedGeneratorConfig::FEED_DELIVERY_OPTIONS as $option) {
            $title .= '<option cost="' . $option['cost'] . '" days="' . $option['days'] . '"/>' . "\n";
        }
        $title .= '</delivery-options>' . "\n" . '<offers>' . "\n";
        return $title;
    }

    private function buildFooter(): string
    {
        return "</offers>\n</shop>\n</yml_catalog>";
    }
}
