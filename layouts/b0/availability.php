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
foreach ($displayData as $department => $amount){
	if ($department != $lastDepartment){
		echo $department . ': ' . $amount . ', ';
	}
	else {
		echo $department . ': ' . $amount;
	}
}
