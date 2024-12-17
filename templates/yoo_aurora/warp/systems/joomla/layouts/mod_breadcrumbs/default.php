<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die;

?>
<ul itemscope itemtype="http://schema.org/BreadcrumbList" class="uk-breadcrumb">
    <?php

	if (!$params->get('showLast', 1)) array_pop($list);

	$count = count($list);

	for ($i = 0; $i < $count; $i ++) {
	
		// clean subtitle from breadcrumb
		if ($pos = strpos($list[$i]->name, '||')) {
			$name = trim(substr($list[$i]->name, 0, $pos));
		} else {
			$name = $list[$i]->name;
		}
		
		// mark-up last item as strong
		if ($i < $count-1) {
			if (!empty($list[$i]->link)) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<a itemprop="item" href="'.$list[$i]->link.'"><span itemprop="name">'.$name.'</span></a>
						<meta itemprop="position" content="'.($i+1).'" />
					  </li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<span itemprop="name">'.$name.'</span>
						<meta itemprop="position" content="'.($i+1).'" />
					  </li>';
			}
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="uk-active">
					<span itemprop="name">'.$name.'</span>
					<meta itemprop="position" content="'.($i+1).'" />
				  </li>';
		}
	}
    ?>
</ul>