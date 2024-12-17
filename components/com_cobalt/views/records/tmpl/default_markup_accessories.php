<?php
defined('_JEXEC') or die();
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Accessory.AccessoryKeys');
JImport('b0.Accessory.AccessoryFiltersKeys');
JImport('b0.Section.Section');
//JImport('b0.Section.PostButtons');
require_once JPATH_ROOT . '/libraries/b0/Accessory/categoriesGenerations.php';
require_once JPATH_ROOT . '/libraries/b0/Accessory/categoriesBodies.php';

/** @var JRegistry $paramsMarkup */
//$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsSection */
//$paramsSection = $this->section->params;
/** @var JRegistry $paramsList */
//$paramsList = $this->tmpl_params['list'];

// Параметры сортировки
$listOrder	= @$this->ordering;
$listDirn	= @$this->ordering_dir;
$section = new Section($this->section, $this->tmpl_params['markup'], $this->category, $this->pagination->pagesCurrent);
//$postButtons = new PostButtons($this->section, $this->category, $this->postbuttons);

$this->document->setTitle($section->metaTitle);
$this->document->setMetaData('description', $section->metaDescription);
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
?>

<!-- Шапка раздела -->
<?= JLayoutHelper::render('b0.Section.head', [
		'section' => $section,
	])
?>
<hr class="uk-article-divider">

<!-- Вывод индекса категории -->
<?php if($this->show_category_index && !$this->worns):?>
	<?= $this->loadTemplate('cindex_'.$this->section->params->get('general.tmpl_category')) ?>
<?php endif;?>

<!-- Панель фильтров -->
<?php if ($section->isCategory) :?>
    <div class="uk-panel uk-panel-box uk-panel-box-secondary">
        <form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
            <!-- Поколение -->
	        <?php if (isset($categoriesGenerations[$this->category->id])):?>
                <p class="uk-h3 uk-text-danger uk-text-center-small">Выберите поколение <?= $this->category->title?></p>
                <ul class="uk-grid uk-text-center" data-uk-grid-margin>
			        <?php foreach ($categoriesGenerations[$this->category->id] as $key => $generation):?>
				        <?php if (isset($this->worns[AccessoryKeys::KEY_GENERATION])){
					        $checked = in_array($generation['value'], $this->worns[AccessoryKeys::KEY_GENERATION]->value['value'], true);
				        }
				        else {
					        $checked = false;
				        } ?>
                        <li class="uk-width-medium-1-4">
                            <div class="uk-panel uk-panel-box uk-panel-box-secondary" style="<?= !$checked ? 'border: none' : '' ?>">
                                <p class="uk-h4 uk-text-center">
							        <?= $generation['name'] ?>
                                    <input type="checkbox" class="uk-margin-left"
                                           name="filters[<?= AccessoryKeys::KEY_GENERATION ?>][value][]"
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
            <!-- Кузов -->
	        <?php if (isset($categoriesBodies[$this->category->id])):?>
                <p class="uk-h3 uk-text-danger uk-text-center-small">Выберите кузов <?= $this->category->title?></p>
                <ul class="uk-grid uk-text-center" data-uk-grid-margin>
			        <?php foreach ($categoriesBodies[$this->category->id] as $key => $body):?>
				        <?php if (isset($this->worns[AccessoryKeys::KEY_BODY])){
					        $checked = in_array($body['value'], $this->worns[AccessoryKeys::KEY_BODY]->value['value'], true);
				        }
				        else {
					        $checked = false;
				        } ?>
                        <li class="uk-width-medium-1-4">
                            <div class="uk-panel uk-panel-box uk-panel-box-secondary" style="<?= !$checked ? 'border: none' : '' ?>">
                                <p class="uk-h4 uk-text-center">
							        <?= $body['name'] ?>
                                    <input type="checkbox" class="uk-margin-left"
                                           name="filters[<?= AccessoryKeys::KEY_BODY ?>][value][]"
                                           value="<?= $body['value'] ?>"
								        <?= $checked ? ' checked="checked"' : '' ?>
                                    >
                                </p>
                                <img src="<?= $body['img'] ?>" width="175" height="80" alt="<?= $body['alt'] ?>">
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
                               placeholder="Название или код" name="filter_search"
                               value="<?= htmlentities($this->state->get('records.search'), ENT_COMPAT, 'utf-8') ?>"/>
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
                    'typeName' => 'Аксессуар'
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
                                <?= JHtml::_('mrelements.sort',  'Цене', 'field^'.AccessoryKeys::KEY_PRICE_GENERAL.'^digits', $listDirn, $listOrder) ?>
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
/*	if ($this->category->id == AccessoryIds::ID_CATEGORY_LARGUS){
		echo '<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-large-bottom">';
		echo '<p class="uk-h3 uk-text-center uk-text-danger">Внимание! В разделе ведутся технические работы</p>';
		echo '<p class="uk-h5 uk-text-center">
            Во избежание любых недоразумений, уточняйте актуальную информацию о ценах и наличии товаров, нюансах оказания услуг в магазинах либо сервисных центрах StoVesta в городе Санкт-Петербург.
        </p>';
		echo '</div>';
	}*/
	echo $this->loadTemplate('list_'.$this->list_template);?>
    <hr class="uk-article-divider">
    <form method="post" class="uk-margin-bottom-remove">
        <div class="uk-text-center">
            <small>
                <?= '<span class="uk-margin-right">'. $this->pagination->getPagesCounter() . '</span>' ?>
                <?= str_replace(['<option value="0">'.JText::_('JALL').'</option>', '<option value="100">100</option>'], '', $this->pagination->getLimitBox()) ?>
                <?= '<span class="uk-margin-left">'. $this->pagination->getResultsCounter() . '</span>' ?>
            </small>
        </div>
        <div class="uk-text-center pagination uk-margin-remove">
            <?= $this->pagination->getPagesLinks() ?>
        </div>
    </form>
<?php }
elseif($this->worns && $section->isCategory) {
	echo '<p class="uk-h4 uk-text-center">К сожалению, по Вашему запросу ничего не найдено. Попробуйте:</p>';
	if (isset($this->worns['search'])) {
		echo '<p class="uk-h4 uk-text-center">- изменить поисковую фразу</p>';
	}
	echo '<p class="uk-h4 uk-text-center">- сбросить все фильтры</p>';
}
elseif ($section->isSection){
	echo JLayoutHelper::render('b0.Section.popular', [
		'id' => $section->modulePopularWorks,
        'title' => 'Популярные аксессуары Лада'
	]);
}
else {
	echo '<p class="uk-h4 uk-text-danger uk-text-center">Раздел находится в разработке</p>';
}
