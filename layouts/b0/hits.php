<?php
/** @var array $displayData
 * $displayData['hits'] - количество просмотров статьи
 */
defined('JPATH_BASE') or die;
echo '<i class="uk-icon-eye"></i><em> Просмотров: </em>'.$displayData['hits'];
