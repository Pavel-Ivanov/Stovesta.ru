<?php
defined('_JEXEC') or die();
/**
 * @var array $displayData
 */
if (!isset($displayData)) {
    exit('Отсутствуют параметры вывода');
}
echo '<hr class="uk-margin-large">';
if (!empty($displayData['title'])) {
    echo '<h2>' . $displayData['title'] . '</h2>';
}
$module = JModuleHelper::getModuleById($displayData['id']); //получаем параметры модуля по ID
echo JModuleHelper::renderModule($module); // Выводим модуль
