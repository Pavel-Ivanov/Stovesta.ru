<?php
defined('_JEXEC') or die();

/**
 * @var array $displayData
 */
if (!$displayData){
	return;
}
$keys = array_keys($displayData);
$lastDepartment = end($keys);
echo '<ul class="uk-list uk-margin-top-remove">';
foreach ($displayData as $department => $amount){
	echo '<li>'.$department . ': ' . $amount.'</li>';
}
echo '</ul>';