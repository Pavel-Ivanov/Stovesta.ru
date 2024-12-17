<?php
defined('_JEXEC') or die();

//$app = JFactory::getApplication();
/** @var JRegistry $paramsMarkup */
//$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsList */
//$paramsList = $this->tmpl_params['list'];

JFactory::getDocument()->setTitle($this->section->params['more']->metakey);
$this->document->setMetaData('description', $this->section->params['more']->metadesc);
$this->document->setMetaData('keywords', '');
?>
<!-- Шапка раздела -->
<h1>
    <?= $this->title ?>
</h1>

<!-- Панель меню -->
<div class="uk-navbar uk-margin-top">
    <div class="uk-navbar-nav">
    </div>
    <div class="uk-navbar-flip">
        <ul class="uk-subnav uk-subnav-line">
            <!-- Добавить запись -->
            <?php if(!empty($this->postbuttons)) :
                echo JLayoutHelper::render('b0.addItem', [
                    'postButtons' => $this->postbuttons,
                    'section' => $this->section,
                    'category' => $this->category,
                    'typeName' => 'Вакансию'
                ]);
            endif; ?>
        </ul>
    </div>
</div>
<hr class="uk-article-divider">
<?php if($this->items):?>
    <!-- Индекс категории -->
    <ul class="">
        <?php foreach ($this->items as $item): ?>
            <li><a href="#<?= $item->alias ?>"><?= $item->title ?></a></li>
        <?php endforeach;?>
    </ul>

    <!-- Список статей -->
    <?= $this->loadTemplate('list_'.$this->list_template) ?>
<?php endif;?>
