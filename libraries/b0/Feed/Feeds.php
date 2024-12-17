<?php
defined('_JEXEC') or die();
JImport('b0.Fixtures');
require_once JPATH_ROOT . '/libraries/b0/Feed/feed-config.php';

class Feeds
{
	public function create()
	{
		/** @var array $feedConfig */
		foreach ($feedConfig as $itemConfig) {
			//$feed = new Feed($itemConfig);
			b0dd($itemConfig);
		}
	}
}