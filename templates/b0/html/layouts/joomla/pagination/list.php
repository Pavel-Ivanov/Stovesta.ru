<?php
defined('_JEXEC') or die;
/**
 * @var array $displayData
 */
$list = $displayData['list'];
$currentId = 0;
foreach ($list['pages'] as $id => $page) {
	if (!$page['active']) {
		$currentId = $id;
	}
}
$range = 2;
?>
<ul>
	<li><?= str_ireplace([' class="hasTooltip pagenav"', 'title="<<"'], '', $list['start']['data']) ?></li>
	<li><?= str_ireplace([' class="hasTooltip pagenav"', 'title="<"'], '', $list['previous']['data']) ?></li>
	<?php
	foreach ($list['pages'] as $id => $page) {
		if ($id <= $currentId+$range && $id >= $currentId-$range) {
			echo '<li>' . $page['data'] . '</li>';
		}
	}
	?>
	<li><?= str_ireplace([' class="hasTooltip pagenav"', 'title=">"'], '', $list['next']['data']) ?></li>
	<li><?= str_ireplace([' class="hasTooltip pagenav"', 'title=">>"'], '', $list['end']['data']) ?></li>
</ul>
