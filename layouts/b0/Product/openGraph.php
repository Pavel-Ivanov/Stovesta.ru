<?php
defined('_JEXEC') or die();
/**
 * @var array $displayData
 */
$tag = '';
foreach ($displayData['og'] as $key => $value) {
	switch ($key) {
		case 'og:image':
			foreach ($value as $key1 => $item1) {
				foreach ($item1 as $key2 => $item2) {
					$tag .= '<meta property="'.$key2 . '" content="'.$item2.'">'.PHP_EOL;
				}
			}
			break;
		case 'og:video':
			foreach ($value as $key1 => $item1) {
				$tag .= '<meta property="'.$key1 . '" content="'.$item1.'">'.PHP_EOL;
			}
			break;
		default:
			$tag .= '<meta property="'.$key . '" content="'.$value.'">'.PHP_EOL;
			break;
	}
}
$displayData['doc']->addCustomTag($tag);
