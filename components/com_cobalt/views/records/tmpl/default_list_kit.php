<?php
defined('_JEXEC') or die('Restricted access');
//JImport('b0.fixtures');
//b0dd($this->items);
if(!$this->items) return;
?>

<div class="uk-grid uk-grid-width-1-1">
    <?php foreach ($this->items as $item):?>
        <h2 class="uk-article-title">
            <a href="<?= JRoute::_($item->url);?>" target="_blank">
                <?= $item->title?>
            </a>
        </h2>
        <hr class="uk-article-divider">
    <?php endforeach;?>
</div>