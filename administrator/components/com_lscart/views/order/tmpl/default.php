<?php
defined('_JEXEC') or die();
$products = json_decode($this->item->data, true, 512, JSON_THROW_ON_ERROR);
?>
<div class="row-fluid">
    <div class="row-fluid">
        <div class="span6">
            <h2>Заказ <?= $this->item->order_id ?> от <?= $this->item->created ?></h2>
        </div>
        <div class="span6">
            <p class="text-right">
                <a href="<?= JURI::base() ?>index.php?option=com_lscart" class="text-right">
                    Вернуться назад
                </a>
            </p>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <dl class="dl-horizontal">
                <dt>Имя</dt><dd><?= $this->item->name ?></dd>
                <dt>Телефон</dt><dd><?= $this->item->phone ?></dd>
                <dt>Email</dt><dd><?= $this->item->email ?></dd>
                <dt>Синхронизировано</dt><dd><?= $this->item->in_sync ?></dd>
                <dt>Дата синхронизации</dt><dd><?= $this->item->synchronized ?></dd>
            </dl>
        </div>
        <div class="span12">
            <h3>Товары</h3>
        </div>
        <div class="span12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Код товара</th>
                        <th>Наименование</th>
                        <th>Количество</th>
                        <th>Цена</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['code']?></td>
                            <td><?= $product['title']?></td>
                            <td><?= $product['quantity']?></td>
                            <td><?= $product['price']?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>
