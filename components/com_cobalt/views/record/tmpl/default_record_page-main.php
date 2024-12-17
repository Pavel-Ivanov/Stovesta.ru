<?php
defined('_JEXEC') or die();

// Удаляем каноническую ссылку
foreach($this->document->_links as $lk => $dl) {
    if($dl['relation'] === 'canonical') {
        unset($this->document->_links[$lk]);
    }
}
unset($this->document->_generator);
?>
<div class="uk-margin-top uk-width-medium-1-1">
    <ul class="uk-tab" data-uk-tab="{connect:'#tab-content1'}">
        <li class="uk-active"><a href="#">Новости</a></li>
        <li><a href="#">Новинки</a></li>
        <li><a href="#">Спецпредложения</a></li>
    </ul>
    <ul id="tab-content1" class="uk-switcher uk-margin">
        <li class="">
            <?= JLayoutHelper::render('b0.module', [
                'title' => '',
                'id' => '137',
            ]) ?>
        </li>
        <li class="">
            <?= JLayoutHelper::render('b0.module', [
                'title' => '',
                'id' => '153',
            ]) ?>
        </li>
        <li class="">
            <?= JLayoutHelper::render('b0.module', [
                'title' => '',
                'id' => '154',
            ]) ?>

        </li>
    </ul>
</div>
<article class="uk-article">
    <hr class="uk-article-divider">
    <?= $this->item->fields_by_id[12]->result ?>
</article>