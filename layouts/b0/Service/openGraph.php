<?php
defined('_JEXEC') or die();
/**
 * @var array $displayData
 */

$tag = '';
foreach ($displayData['og'] as $key => $value) {
	if (is_array($value)) {
		foreach ($value as $key1 => $item1) {
			foreach ($item1 as $key2 => $item2) {
				$tag .= '<meta property="'.$key2 . '" content="'.$item2.'">'.PHP_EOL;
			}
		}
	}
	else  {
		$tag .= '<meta property="'.$key . '" content="'.$value.'">'.PHP_EOL;
	}
}
$displayData['doc']->addCustomTag($tag);
