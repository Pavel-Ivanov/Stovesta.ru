<?php
defined('_JEXEC') or die();
JImport('b0.Sparepart.SparepartKeys');
JImport('b0.Sparepart.SparepartIds');
JImport('b0.Sparepart.SparepartFiltersKeys');
JImport('b0.Kit.Kits');

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
/** @var JRegistry $paramsMarkup */
$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsList */
$paramsList = $this->tmpl_params['list'];

// Параметры сортировки
$listOrder	= @$this->ordering;
$listDirn	= @$this->ordering_dir;

if ($this->category->id !== 0) {
    $kits = new Kits($this->section->id, $this->category->id, $this->worns);
}

if ($this->category->id) {
    $title = 'Запчасти ' . $this->category->title;
    $subTitle = $this->category->description;
    $metaTitle = $this->category->metakey . ($this->pagination->pagesCurrent > 1 ? ' - Страница '.$this->pagination->pagesCurrent : '');
	$metaDescription = $this->category->metadesc . ($this->pagination->pagesCurrent > 1 ? ' - Страница '.$this->pagination->pagesCurrent : '');
}
else {
    $title = $this->section->title;
    $subTitle = $this->section->description;
    $metaTitle = $this->section->params['more']->metakey;
	$metaDescription = $this->section->params['more']->metadesc;
}
$this->document->setTitle($metaTitle);
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

$categoriesGenerations = [
	SparepartIds::ID_CATEGORY_VESTA => [
		'vesta' => [
			'value' => 'Lada Vesta',
			'name' => 'Lada Vesta',
			'years' => '2015-2022',
//			'title' => 'Запчасти для Lada Vesta',
			'img' => '/images/icons-cars/icon-vesta-sedan.png',
			'alt' => 'Запчасти для Лада Веста 2015-2022',
		],
		'vesta-ng' => [
			'value' => 'Lada Vesta NG',
			'name' => 'Lada Vesta NG',
			'years' => '2023-',
//			'title' => 'Запчасти для Lada Vesta',
			'img' => '/images/icons-cars/icon_vesta-ng.png',
			'alt' => 'Запчасти для Лада Веста 2023-',
		],
	],
/*	SparepartIds::ID_CATEGORY_XRAY => [
		'xray' => [
			'value' => 'Lada XRay',
			'name' => 'Lada XRay',
			'years' => '2015-2021',
//			'title' => 'Запчасти для Lada XRay',
			'img' => '/images/icons-cars/icon-xray.png',
			'alt' => 'Запчасти для Лада ИксРей 2015-2021',
		],
	],*/
/*	SparepartIds::ID_CATEGORY_GRANTA_FL => [
		'granta-fl' => [
			'value' => 'Lada Granta FL',
			'name' => 'Lada Granta FL',
			'years' => '2018-2021',
//			'title' => 'Запчасти для Lada Granta FL',
			'img' => '/images/icons-cars/icon-granta-fl-sedan.png',
			'alt' => 'Запчасти для Лада Гранта FL 2018-2021',
		],
	],*/
/*	SparepartIds::ID_CATEGORY_LARGUS => [
		'largus' => [
			'value' => 'Lada Largus',
			'name' => 'Lada Largus',
			'years' => '2012-2021',
//			'title' => 'Запчасти для Lada Largus',
			'img' => '/images/icons-cars/icon-largus-wagon.png',
			'alt' => 'Запчасти для Лада Ларгус 2012-2021',
		],
	],*/
];
?>
<!-- Шапка раздела -->
<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-2-10 uk-text-center">
        <?php if ($this->category->id):?>
            <img src="/<?= $this->category->image ?>" alt="">
        <?php else:?>
            <img src="<?= $paramsMarkup['main']->section_icon ?>" alt="">
        <?php endif;?>
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
<?php if($this->show_category_index && !$this->worns):?>
	<?= $this->loadTemplate('cindex_'.$this->section->params->get('general.tmpl_category')) ?>
<?php endif;?>

<!-- Панель фильтров -->
<?php if ($this->category->id !== 0) :?>
    <div class="uk-panel uk-panel-box uk-panel-box-secondary">
        <?php if ($kits->kits) : ?>
            <?php $kits->kitsRender();?>
            <hr class="uk-article-divider">
        <?php endif; ?>
        <form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
            <!-- Поколение -->
            <?php if (isset($categoriesGenerations[$this->category->id])):?>
                <p class="uk-h4 uk-text-center-small">Выберите поколение <?= $this->category->title?></p>
                <ul class="uk-grid uk-text-center" data-uk-grid-margin>
                    <?php foreach ($categoriesGenerations[$this->category->id] as $key => $generation):?>
                        <?php if (isset($this->worns[SparepartKeys::KEY_GENERATION])){
                            $checked = in_array($generation['value'], $this->worns[SparepartKeys::KEY_GENERATION]->value['value'], true);
                        }
                        else {
                            $checked = false;
                        } ?>
                        <li class="uk-width-medium-1-5">
                            <div class="uk-panel uk-panel-box uk-panel-box-secondary" style="<?= !$checked ? 'border: none' : '' ?>">
                                <p class="uk-h3 uk-text-center">
                                    <?= $generation['name'] ?>
                                    <input type="checkbox" class="uk-margin-left"
                                           name="filters[<?= SparepartKeys::KEY_GENERATION ?>][value][]"
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
            <div class="uk-form-row">
                <ul class="uk-subnav uk-text-center-small">
                    <li class="uk-text-center-small">
                        <span class="uk-margin-right">Я ищу</span>
                        <input type="text" id="form-h-it" class="uk-form-width-medium uk-form-danger"
                               placeholder="Название или код" name="filter_search"
                               value="<?= htmlentities($this->state->get('records.search'), ENT_COMPAT, 'utf-8') ?>"/>
                    </li>
                    <li class="uk-text-center-small">
                        <span class="uk-margin-left uk-margin-right">для</span>
                        <?= isset($this->filters[SparepartKeys::KEY_MOTOR]) ? $this->filters[SparepartKeys::KEY_MOTOR]->onRenderFilter($this->section) : '' ?>
                    </li>
                    <li class="uk-text-center-small">
                        <?= isset($this->filters[SparepartKeys::KEY_CATEGORY]) ? $this->filters[SparepartKeys::KEY_CATEGORY]->onRenderFilter($this->section) : '' ?>
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
    </div>

<?php endif;?>

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
                    'typeName' => 'Запчасть'
                ]);
            endif; ?>
            <!-- Переключение шаблонов -->
<!--            <?php /*if ($this->items) : */?>
                <li>
                    <a href="#" onclick="Cobalt.applyFilter('filter_tpl', 'spareparts-panel.c894ede944f94c7ae93181d519e6300b')">
                        <i class="uk-icon-th-large"></i>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="Cobalt.applyFilter('filter_tpl', 'spareparts.c894ede944f94c7ae93181d519e6300b')">
                        <i class="uk-icon-th-list"></i>
                    </a>
                </li>
            --><?php /*endif; */?>
            <!-- Сортировка -->
            <?php if ($this->items) : ?>
                <li data-uk-dropdown="{mode:'click'}">
                    <a href="#">
                        <i class="uk-icon-sort"></i>&nbsp;Сортировать по&nbsp;<i class="uk-icon-caret-down"></i>
                    </a>
                    <div class="uk-dropdown uk-panel uk-panel-box uk-panel-box-secondary">
                        <ul class="uk-nav uk-nav-dropdown">
<!--                            <li>
                                <?/*= JHtml::_('mrelements.sort', 'Сначала недорогие', 'field^'.SparepartKeys::KEY_PRICE_GENERAL.'^digits', 'asc', 'asc') */?>
                            </li>
                            <li>
                                <?/*= JHtml::_('mrelements.sort', 'Сначала дорогие', 'field^'.SparepartKeys::KEY_PRICE_GENERAL.'^digits', 'desc', 'desc') */?>
                            </li>
-->                            <li>
                                <?= JHtml::_('mrelements.sort', 'Наименованию', 'r.title', $listDirn, $listOrder) ?>
                            </li>
                            <li>
                                <?= JHtml::_('mrelements.sort', 'Популярности', 'r.hits', $listDirn, $listOrder) ?>
                            </li>
                            <li>
                                <?= JHtml::_('mrelements.sort',  'Цене', 'field^'.SparepartKeys::KEY_PRICE_GENERAL.'^digits', $listDirn, $listOrder) ?>
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
/*	if ($this->category->id == SparepartIds::ID_CATEGORY_LARGUS){
        echo '<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-large-bottom">';
		echo '<p class="uk-h3 uk-text-center uk-text-danger">Внимание! В разделе ведутся технические работы</p>';
		echo '<p class="uk-h5 uk-text-center">
            Во избежание любых недоразумений, уточняйте актуальную информацию о ценах и наличии товаров, нюансах оказания услуг в магазинах либо сервисных центрах StoVesta в городе Санкт-Петербург.
        </p>';
        echo '</div>';
	}*/
    echo $this->loadTemplate('list_'.$this->list_template);?>
    <hr class="uk-article-divider">
	<?php if ($this->tmpl_params['list']->def('tmpl_core.item_pagination', 1)) : ?>
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
	<?php endif;
}
elseif($this->worns && $this->category->id !== 0) {
	echo '<p class="uk-h4 uk-text-center">К сожалению, по Вашему запросу ничего не найдено. Попробуйте:</p>';
	if (isset($this->worns['search'])) {
		echo '<p class="uk-h4 uk-text-center">- изменить поисковую фразу</p>';
	}
	echo '<p class="uk-h4 uk-text-center">- сбросить все фильтры</p>';
}
elseif (!$this->category->id){
	echo '<hr class="uk-margin-large">';
    echo JLayoutHelper::render('b0.module', [
            'title' => 'Популярные запчасти',
            'id' => $paramsMarkup['main']->module_minibanners,
    ]);
}
else {
	echo '<p class="uk-h4 uk-text-danger uk-text-center">Раздел находится в разработке</p>';
}
