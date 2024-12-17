<?php
defined('_JEXEC') or die();
JImport('b0.fixtures');

if (empty($this->items)) {
	echo 'Ничего нет';
}
?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th width="12%">id</th>
            <th width="10%">phone</th>
            <th width="10%">created</th>
            <th width="60%">data</th>
            <th width="2%">in_sync</th>
            <th width="6%">synchronized</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $item): ?>
            <tr>
                <td>
                    <a href="<?= JRoute::_('index.php?option=com_lscart&view=order&id='.$item->id) ?>">
                        <?= $item->order_id ?>
                    </a>
                </td>
                <td><?= $item->phone ?></td>
                <td><?= $item->created ?></td>
                <td><?= $item->data ?></td>
                <td><?= $item->in_sync ?></td>
                <td><?= $item->synchronized ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
