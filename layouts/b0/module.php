<?php
defined('_JEXEC') or die();
JImport( 'joomla.application.module.helper' );
/**
 * @var array $displayData
 */
if (!isset($displayData)) {
    exit('Отсутствуют параметры вывода модуля.');
}

if (!empty($displayData['title'])) {
    echo '<h2>' . $displayData['title'] . '</h2>';
}
$module = JModuleHelper::getModuleById($displayData['id']); //получаем параметры модуля по ID
echo JModuleHelper::renderModule($module); // Выводим модуль
