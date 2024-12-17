<?php

/**
 * @param array $tabsTemplate
 * @param array $fields
 *
 * @return array
 *
 * @since version
 */
function setTabs(array $tabsTemplate, array $fields):array
{
	$tabs = [];
	foreach ($tabsTemplate as $key => $tab) {
		if (!isset($fields[$key])) {
			continue;
		}
		$tabs[$key] = [
			'title' => $tab['title'],
			'isActive' => $tab['isActive'],
			'total' => $fields[$key]->content['total'] ?? count($fields[$key]->raw),
			'result' => $fields[$key]->content['html'] ?? $fields[$key]->result,
		];
	}
	
	return $tabs;
}
