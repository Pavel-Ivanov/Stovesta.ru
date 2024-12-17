<?php
defined('JPATH_BASE') or die;

/*if (mb_strlen($displayData['title']) == 0) {
	return;
}*/
echo '<'.$displayData['tag'].'>' . $displayData['title'] . '</'.$displayData['tag'].'>';
