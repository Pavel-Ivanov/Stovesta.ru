<?php
defined('_JEXEC') or die();
JImport('b0.Work.WorkKeys');
JImport('b0.Work.WorkIds');
JImport('b0.Work.WorkFiltersKeys');
//JImport('b0.fixtures');
require_once JPATH_ROOT . '/libraries/b0/Service/categoriesGenerations.php';

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
/** @var JRegistry $paramsMarkup */
$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsSection */
$paramsSection = $this->section->params;
/** @var JRegistry $paramsList */
$paramsList = $this->tmpl_params['list'];

// Параметры сортировки
$listOrder	= @$this->ordering;
$listDirn	= @$this->ordering_dir;

$Section = new stdClass();
//$Section->sectionId = $this->section->id;   // string
//$Section->categoryId = $this->category->id;   // если есть категория - string, если нет - int(0)
//$Section->siteName = JFactory::getApplication()->get('sitename');   // string

//$Section->title = $this->category->id ? $this->section->title . ' ' . $this->category->title : $this->section->title;   // string
//$Section->description = $this->category->id ? $this->category->description : $this->section->description;   // string
//$Section->metaTitle = $this->category->id ? $this->category->metakey : $paramsSection->get('more.metakey');   // string
//$Section->metaDescription = $this->category->id ? $this->category->metadesc : $paramsSection->get('more.metadesc');   // string
//$Section->imageSource = $this->category->id ? $this->category->image : $paramsMarkup->get('main.section_icon');
//$Section->modulePopularWorks = $paramsMarkup->get('main.module_popular');

if ($this->category->id) {
	$title = 'Ремонт ' . $this->category->title;
	$subTitle = $this->category->description;
	$metaTitle = $this->category->metakey . ($this->pagination->pagesCurrent > 1 ? ' - Страница '.$this->pagination->pagesCurrent : '');
	$metaDescription = $this->category->metadesc . ($this->pagination->pagesCurrent > 1 ? ' - Страница '.$this->pagination->pagesCurrent : '');
}
else {
	$title = $this->section->title;
	$subTitle = $this->section->description;
	$metaTitle = $paramsSection->get('more.metakey');
	$metaDescription = $paramsSection->get('more.metadesc');
}
$imageSource = $this->category->id ? $this->category->image : $paramsMarkup->get('main.section_icon');
$modulePopularWorks = $paramsMarkup->get('main.module_popular');

$doc->setTitle($metaTitle);
$this->document->setMetaData('description', $metaDescription);
$this->document->setMetaData('keywords', '');

// Заменяем каноническую ссылку на главной странице раздела
if (!$this->category->id) {
        foreach ($this->document->_links as $lk => $dl) {
            if ($dl['relation'] === 'canonical') {
                unset($this->document->_links[$lk]);
                break;
            }
        }
        $this->document->addHeadLink(JRoute::_(Url::records($this->section, $this->category), TRUE, 1), 'canonical');
    //$this->document->_links[JUri::base(). $this->tmpl_params['record']->get('tmpl_core.link_canonical')] = $canon;
}
//b0dd($doc);
//unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery.min.js']);
//unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-noconflict.js']);
//unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-migrate.min.js']);
//unset($doc->_scripts[JURI::root(true) . '/media/jui/js/bootstrap.min.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-core.js']);
//unset($doc->_scripts[JURI::root(true) . '/media/system/js/core.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/modal.js']);
unset($doc->_scripts[JURI::root(true) . '/repair?task=ajax.mainJS']);
unset($doc->_scripts[JURI::root(true) . '/components/com_cobalt/library/js/felixrating.js']);
unset($doc->_script['text/javascript']);
//b0debug($doc->_scripts);

unset($doc->_styleSheets['/media/system/css/modal.css']);
unset($doc->_styleSheets['/components/com_cobalt/library/css/style.css']);
//b0debug($doc->_styleSheets);
?>

<!--  Шапка раздела -->
<div class="uk-grid" data-uk-grid-margin>
    <!-- Иконка раздела -->
    <div class="uk-width-medium-2-10 uk-text-center">
        <img src="/<?= $imageSource ?>" alt="">
    </div>
    <div class="uk-width-medium-8-10 uk-text-center-small">
        <div class="uk-grid">
            <!-- Заголовок раздела -->
            <div class="uk-width-medium-1-1 uk-margin-top">
                <h1>
					<?= $title ?>
                </h1>
            </div>
            <!-- Описание раздела -->
            <div class="uk-width-medium-1-1 uk-hidden-small">
                <hr class="uk-article-divider">
                <p class="ls-sub-title">
					<?= $subTitle ?>
                </p>
            </div>
        </div>
    </div>
</div>
<hr class="uk-article-divider">

<!-- Вывод индекса категории -->
<?php if($this->show_category_index):?>
	<?= $this->loadTemplate('cindex_'.$this->section->params->get('general.tmpl_category'))?>
<?php endif;?>

<!-- Панель фильтров -->
<?php if ($this->category->id !== 0) :?>
<div class="uk-panel uk-panel-box uk-panel-box-secondary">
    <form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
        <!-- Поколение -->
	    <?php if (isset($categoriesGenerations[$this->category->id])):?>
            <p class="uk-h4 uk-text-center-small">Выберите поколение <?= $this->category->title?></p>
            <ul class="uk-grid uk-text-center" data-uk-grid-margin>
			    <?php foreach ($categoriesGenerations[$this->category->id] as $key => $generation):?>
				    <?php if (isset($this->worns[WorkKeys::KEY_GENERATION])){
					    $checked = in_array($generation['value'], $this->worns[WorkKeys::KEY_GENERATION]->value['value'], true);
				    }
				    else {
					    $checked = false;
				    } ?>
                    <li class="uk-width-medium-1-4">
                        <div class="uk-panel uk-panel-box uk-panel-box-secondary" style="<?= !$checked ? 'border: none' : '' ?>">
                            <p class="uk-h3 uk-text-center">
							    <?= $generation['name'] ?>
                                <input type="checkbox" class="uk-margin-left"
                                       name="filters[<?= WorkKeys::KEY_GENERATION ?>][value][]"
                                       value="<?= $generation['value'] ?>"
								    <?= $checked ? ' checked="checked"' : '' ?>
                                >
                                <br/>
                                <span class="uk-h5"><?= $generation['years'] ?></span>
                            </p>
                            <img src="<?= $generation['img'] ?>" width="175" height="80" alt="<?= $generation['alt'] ?>">
                        </div>
                    </li>
			    <?php endforeach;?>
            </ul>
            <hr class="uk-grid-divider">
	    <?php endif;?>
        <!-- Общий поиск -->
        <div class="uk-form-row">
            <ul class="uk-subnav uk-text-center-small">
                <li class="uk-text-center-small">
                    <span class="uk-margin-right">Я ищу</span>
                    <input type="text" id="form-h-it" class="uk-form-width-medium uk-form-danger"
                           placeholder="Название работы" name="filter_search"
                           value="<?= htmlentities($this->state->get('records.search'), ENT_COMPAT, 'utf-8') ?>"/>
                </li>
                <li class="uk-text-center-small">
                    <span class="uk-margin-left uk-margin-right">для</span>
	                <?= isset($this->filters[WorkKeys::KEY_MOTOR]) ? $this->filters[WorkKeys::KEY_MOTOR]->onRenderFilter($this->section) : '' ?>
                </li>
                <li class="uk-text-center-small">
	                <?= isset($this->filters[WorkKeys::KEY_CATEGORY]) ? $this->filters[WorkKeys::KEY_CATEGORY]->onRenderFilter($this->section) : '' ?>
                </li>
                <li class="uk-text-center-small">
                    <button class="uk-button uk-button-success" type="button" title="Применить фильтры"
                            onclick="Joomla.submitbutton('records.filters')">Показать
                    </button>
                    <button class="uk-button uk-button-primary" type="button" title="Сбросить фильтры"
                            onclick="Joomla.submitbutton('records.cleanall')">Сбросить
                    </button>
                </li>
            </ul>
        </div>
        <!-- Конец вывода фильтров -->

        <input type="hidden" name="section_id" value="<?= $this->state->get('records.section_id') ?>">
        <input type="hidden" name="cat_id" value="<?= $app->input->getInt('cat_id') ?>">
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
</div>
<?php endif;?>
<!-- Конец панели фильтров -->

<!-- Панель меню -->
<div class="uk-navbar uk-margin-top">
    <div class="uk-navbar-nav">
    </div>
    <div class="uk-navbar-flip">
        <ul class="uk-subnav uk-subnav-line">
            <!-- Добавить запись -->
            <?php if(!empty($this->postbuttons)) :
                echo JLayoutHelper::render('b0.addItems', [
                    'postButtons' => $this->postbuttons,
                    'section' => $this->section,
                    'category' => $this->category,
                    'typeName' => 'Работу'
                ]);
            endif; ?>
            <!-- Сортировка -->
            <?php if ($this->items) : ?>
                <li data-uk-dropdown="{mode:'click'}">
                    <a href="#">
                        <i class="uk-icon-sort"></i>&nbsp;Сортировать по&nbsp;<i class="uk-icon-caret-down"></i>
                    </a>
                    <div class="uk-dropdown uk-panel uk-panel-box uk-panel-box-secondary">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li>
                                <?= JHtml::_('mrelements.sort', 'Наименованию', 'r.title', $listDirn, $listOrder) ?>
                            </li>
                            <li>
                                <?= JHtml::_('mrelements.sort', 'Популярности', 'r.hits', $listDirn, $listOrder) ?>
                            </li>
                            <li>
                                <?= JHtml::_('mrelements.sort',  'Цене', 'field^'.WorkKeys::KEY_PRICE_GENERAL.'^digits', $listDirn, $listOrder) ?>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Вывод статей -->
<?php
if($this->items) {
/*    if ($this->category->id == WorkIds::ID_CATEGORY_LARGUS){
        echo '<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-large-bottom">';
        echo '<p class="uk-h3 uk-text-center uk-text-danger">Внимание! В разделе ведутся технические работы</p>';
        echo '<p class="uk-h5 uk-text-center">
            Во избежание любых недоразумений, уточняйте актуальную информацию о ценах и наличии товаров, нюансах оказания услуг в магазинах либо сервисных центрах StoVesta в городе Санкт-Петербург.
        </p>';
        echo '</div>';
    }*/
    echo $this->loadTemplate('list_'.$this->list_template);
	if ($this->tmpl_params['list']->def('tmpl_core.item_pagination', 1)) {?>
        <form method="post" class="uk-margin-bottom-remove">
            <div class="uk-text-center">
                <small>
					<?php if($this->pagination->getPagesCounter()):?>
						<?= '<span class="uk-margin-right">'. $this->pagination->getPagesCounter() . '</span>' ?>
					<?php endif;?>
					<?php if ($this->tmpl_params['list']->def('tmpl_core.item_limit_box', 0)) : ?>
						<?= str_replace(['<option value="0">'.JText::_('JALL').'</option>', '<option value="100">100</option>'], '', $this->pagination->getLimitBox()) ?>
					<?php endif; ?>
					<?= '<span class="uk-margin-left">'. $this->pagination->getResultsCounter() . '</span>' ?>
                </small>
            </div>
            <div class="uk-text-center pagination uk-margin-remove">
				<?= $this->pagination->getPagesLinks() ?>
            </div>
        </form>
	<?php }
}
elseif($this->worns && $this->category->id !== 0) {
    echo '<p class="uk-h4 uk-text-center">К сожалению, по Вашему запросу ничего не найдено. Попробуйте:</p>';
    if (isset($this->worns['search'])) {
        echo '<p class="uk-h4 uk-text-center">- изменить поисковую фразу</p>';
    }
    if (isset($this->worns[WorkKeys::KEY_GENERATION])) {
        echo '<p class="uk-h4 uk-text-center">- изменить поколение</p>';
    }
    echo '<p class="uk-h4 uk-text-center">- сбросить все фильтры</p>';
}
elseif (!$this->category->id) {
    echo '<hr class="uk-margin-large">';
    echo JLayoutHelper::render('b0.module', [
        'title' => 'Популярные работы',
        'id' => $modulePopularWorks,
    ]);

}
else {
	echo '<p class="uk-h4 uk-text-danger uk-text-center">Раздел находится в разработке</p>';
}
