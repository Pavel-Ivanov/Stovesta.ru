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
    <li class="pagination-start"><?= str_ireplace(['>В начало<'], ['>&lt;&lt;<'], $list['start']['data']) ?></li>
    <li class="pagination-prev"><?= str_ireplace(['>Назад<'], ['>&lt;<'], $list['previous']['data']) ?></li>
    <?php
    foreach ($list['pages'] as $id => $page) {
        if ($id <= $currentId+$range && $id >= $currentId-$range) {
            echo '<li>' . $page['data'] . '</li>';
        }
    }
    ?>
    <li class="pagination-next"><?= str_ireplace(['>Вперед<'], ['>&gt;<'], $list['next']['data']) ?></li>
    <li class="pagination-end"><?= str_ireplace(['>В конец<'], ['>&gt;&gt;<'], $list['end']['data']) ?></li>
</ul>
