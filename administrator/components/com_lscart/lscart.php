<?php
defined('_JEXEC') or die();

$controller = JControllerLegacy::getInstance('Lscart');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task', 'display'));
$controller->redirect();
