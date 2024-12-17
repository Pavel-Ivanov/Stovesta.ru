<?php
defined('_JEXEC') or die();

$doc = JFactory::getDocument();
b0clearGenerator($doc);
b0clearScripts($doc, [
    '/media/jui/js/jquery-noconflict.js',
    '/media/jui/js/jquery-migrate.min.js',
    '/media/jui/js/bootstrap.min.js',
    '/media/system/js/mootools-core.js',
    '/media/system/js/mootools-more.js',
//    '/media/system/js/core.js',
    '/media/system/js/modal.js',
    '/components/com_cobalt/library/js/felixrating.js'
]);
b0clearScript($doc);
b0clearStyleSheets($doc, [
    '/media/system/css/modal.css',
    '/components/com_cobalt/library/css/style.css',
    '/components/com_jce/editor/tiny_mce/plugins/columns/css/content.css',
    '/plugins/system/jce/css/content.css'
]);

$markup = $this->tmpl_params['markup'];
$listparams = $this->tmpl_params['list'];

$listOrder	= @$this->ordering;
$listDirn	= @$this->ordering_dir;

$this->document->setMetaData('description', $this->section->params['more']->metadesc . ($this->pagination->pagesCurrent > 1 ? ' Страница '.$this->pagination->pagesCurrent : ''));

switch ($listOrder) {
    case 'r.hits':
        $textOrder = 'популярные';
        break;
    case 'r.ctime':
        $textOrder = 'новые';
        break;
    default:
        $textOrder = 'новые';
}
?>

<!-- Шапка раздела -->
<div class="uk-grid data-uk-grid-margin">
    <!-- Иконка раздела -->
    <div class="uk-width-small-1-6">
        <img src="/images/icons-section/icon-news.png" alt="<?= $this->description ?>">
    </div>

    <div class="uk-width-small-5-6">
        <div class="uk-grid data-uk-grid-margin">
            <!-- Заголовок раздела -->
            <div class="uk-width-small-1-1">
                <h1>
                    <?= $this->title ?>
                </h1>
            </div>
            <!-- Описание раздела -->
            <div class="uk-width-medium-1-1 uk-hidden-small">
                <hr class="uk-article-divider">
                <p class="ls-sub-title">
                    <?= $this->section->description ?>
                </p>
            </div>
        </div>
    </div>
</div>

<hr class="uk-article-divider">

<form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <input type="hidden" name="section_id" value="<?= $this->state->get('records.section_id') ?>">
    <input type="hidden" name="cat_id" value="<?= JFactory::getApplication()->input->getInt('cat_id') ?>">
    <input type="hidden" name="option" value="com_cobalt">
    <input type="hidden" name="task" value="">
    <input type="hidden" name="limitstart" value="0">
    <input type="hidden" name="filter_order" value="<?= $this->ordering ?>">
    <input type="hidden" name="filter_order_Dir" value="<?= $this->ordering_dir ?>">
    <?= JHtml::_('form.token') ?>
    <?php if ($this->worns): ?>
        <?php foreach ($this->worns as $worn): ?>
            <input type="hidden" name="clean[<?= $worn->name ?>]" id="<?= $worn->name ?>" value="">
        <?php endforeach; ?>
    <?php endif; ?>
</form>

<!-- Панель меню -->
<ul class="uk-subnav uk-subnav-line uk-float-right">
    <?php if(!empty($this->postbuttons)) :
        echo JLayoutHelper::render('b0.addItems', [
            'postButtons' => $this->postbuttons,
            'section' => $this->section,
            'category' => $this->category,
        ]);
    endif; ?>

    <li data-uk-dropdown="{mode:'click'}">
        Сортировка: <a href="#"><?= $textOrder ?><i class="uk-icon-caret-down uk-margin-left"></i></a>
        <div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom">
            <ul class="uk-nav uk-nav-dropdown">
                <li><a href="#" onclick="Joomla.tableOrdering('r.hits','desc','')">популярные</a></li>
                <li><a href="#" onclick="Joomla.tableOrdering('r.ctime','desc','')">новые</a></li>
            </ul>
        </div>
    </li>
</ul>

<div class="uk-clearfix"></div>

<!-- Список статей -->
<?php if($this->items):?>
    <?= $this->loadTemplate('list_'.$this->list_template) ?>
    <hr class="uk-article-divider">
    <?= JLayoutHelper::render('b0.pagination', $this->pagination) ?>
<?php else:?>
    <h4 class="uk-text-center">Нет новостей</h4>
<?php endif;?>
