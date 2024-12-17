<?php
defined('_JEXEC') or die();
JImport('b0.Maintenance.MaintenanceKeys');
JImport('b0.Maintenance.MaintenanceIds');
require_once JPATH_ROOT . '/libraries/b0/Maintenance/' . $this->tmpl_params['markup']->get('main.config_file');

$app = JFactory::getApplication();
$siteName = $app->get('sitename');

$markupParams = $this->tmpl_params['markup'];
$listParams = $this->tmpl_params['list'];
$categoryParams = $this->tmpl_params['markup']->get('main');
$modelName = $this->tmpl_params['markup']->get('main.model_name');
$title = 'Техобслуживание ' . $this->category->title;
$description = $this->category->description;
$metaDescription = $this->category->metadesc;
$metaTitle = empty($this->category->metakey) ? $this->category->description : $this->category->metakey;

JFactory::getDocument()->setTitle($metaTitle);
$this->document->setMetaData('description', $metaDescription);
$this->document->setMetaData('keywords', '');
?>

<!--  Шапка раздела -->
<div class="uk-grid" data-uk-grid-margin>
    <!-- Иконка раздела -->
    <div class="uk-width-medium-2-10">
        <img  class="uk-align-center" src="/<?= $this->category->image ?>" width="175" height="80" alt="<?= $this->description ?>">
    </div>

    <div class="uk-width-medium-8-10">
        <div class="uk-grid" data-uk-grid-margin>
            <!-- Заголовок раздела -->
            <div class="uk-width-1-1">
                <h1 class="uk-text-center-small">
                    <?= $title ?>
                </h1>
            </div>
            <!-- Описание раздела -->
            <div class="uk-width-medium-1-1 uk-hidden-small">
                <hr class="uk-article-divider">
                <p class="ls-sub-title">
	                <?= $description ?>
                </p>
            </div>
        </div>
    </div>
</div>
<hr class="uk-article-divider">

<div class="uk-grid uk-grid-match" data-uk-grid-match data-uk-grid-margin>
    <div class="uk-width-medium-1-5 anchor">
        <a href="#reglament" class="uk-panel uk-panel-box uk-text-center">
            Регламент и стоимость техобслуживания
        </a>
    </div>
    <div class="uk-width-medium-1-5 anchor">
        <a href="#operations" class="uk-panel uk-panel-box uk-text-center">
            Проверочные операции при техобслуживании
        </a>
    </div>
    <div class="uk-width-medium-1-5 anchor">
        <a href="<?= $categoryParams->link_contacts ?>" class="uk-panel uk-panel-box uk-text-center" target="_blank">
            Где пройти техобслуживание
        </a>
    </div>
    <div class="uk-width-medium-1-5 anchor">
        <a href="<?= $categoryParams->link_quarantee ?>" class="uk-panel uk-panel-box uk-text-center" target="_blank">
            Гарантийные обязательства
        </a>
    </div>
    <div class="uk-width-medium-1-5 anchor">
        <a href="#order" class="uk-panel uk-panel-box uk-text-center">
            Записаться на техобслуживание
        </a>
    </div>
</div>

<hr class="uk-article-divider">

<!-- prefix -  -->
<?php if (isset($categoryParams->module_prefix)):?>
    <section id="prefix">
        <div class="uk-margin-top">
            <?= JLayoutHelper::render('b0.module', [
            'title' => '',
            'id' => $categoryParams->module_prefix,
            ]) ?>
        </div>
    </section>
<?php endif;?>

<!-- #reglament - Сколько стоит техобслуживание -->
<section id="reglament">
    <div class="uk-panel uk-panel-box uk-margin-large-top">
        <h2 class="uk-text-center">
            Регламент и стоимость технического обслуживания <?= $modelName ?>
            <a class="tm-totop-scroller" title="вернуться к оглавлению" href="#top" data-uk-smooth-scroll=""></a>
        </h2>
    </div>
<!--    <p>
        Регулярное техническое обслуживание автомобиля <?php /*= $modelName */?>- это определяемый производителем список сервисных работ, выполняемых один раз в год или каждые 15 000 километров.
    </p>
-->    <?php
    /**
     * @var array $config
     */
    foreach ($config as $years => $motors):?>
        <div class="uk-accordion  uk-margin-top" data-uk-accordion="{showfirst:false, collapse: false}">
            <h3 class="uk-accordion-title">
<!--                <i class="uk-icon-plus uk-icon-justify uk-text-muted"></i>-->
                <span class="uk-text-muted uk-icon-justify" style="font-size: 150%">+</span>
                <?= $years ?>
            </h3>
            <!-- Каждый мотор- это отдельная таблица -->
            <div class="uk-accordion-content">
	            <?php foreach ($motors['motors'] as $key => $motor):?>
                    <p class="uk-h3 uk-text-danger uk-text-center-small uk-margin-large-top">Мотор(ы): <?= $key ?></p>
                    <div class="uk-overflow-container">
                        <table class="uk-table ls-table-condensed">
                            <thead>
                                <?php renderThead($motor['type']); ?>
                            </thead>
                            <tbody>
                                <?php renderTbodyOperations($motor);?>
                                <?php renderTbodyLinks($motor);?>
                            </tbody>
                        </table>
                    </div>
	            <?php endforeach;?>
            </div>
        </div>
    <?php endforeach;?>
</section>

<!-- #operations - Проверочные операции -->
<?php if ($categoryParams->module_1):?>
    <section id="operations">
        <div class="uk-panel uk-panel-box uk-margin-large-top">
            <h2 class="uk-text-center">
                Проверочные операции при ТО <?= $modelName ?>
                <a href="#top" class="tm-totop-scroller" title="вернуться к оглавлению" data-uk-smooth-scroll=""></a>
            </h2>
        </div>
        <div class="uk-margin-top">
            <?= JLayoutHelper::render('b0.module', [
                'title' => '',
                'id' => $categoryParams->module_1,
            ]) ?>
        </div>
    </section>
<?php endif;?>

<!-- suffix -  -->
<?php if (isset($categoryParams->module_suffix)):?>
    <section id="suffix">
        <div class="uk-margin-top">
            <?= JLayoutHelper::render('b0.module', [
                'title' => '',
                'id' => $categoryParams->module_suffix,
            ]) ?>
        </div>
    </section>
<?php endif;?>

<!-- #order - Записаться на ТО -->
<?php if ($categoryParams->module_2):?>
    <section id="order">
    <div class="uk-panel uk-panel-box uk-margin-large-top">
        <h2 class="uk-text-center">
            Записаться на ТО <?= $modelName ?>
            <a class="tm-totop-scroller" title="вернуться к оглавлению" href="#" data-uk-smooth-scroll=""></a>
        </h2>
    </div>

    <div class="uk-grid uk-margin-large-top uk-grid-match" data-uk-grid-match data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <div class="uk-text-center">
                <?= JLayoutHelper::render('b0.module', [
                    'title' => '',
                    'id' => $categoryParams->module_2,
                ]) ?>
            </div>
        </div>
        <div class="uk-width-medium-1-2">
            <div class="uk-text-center">
                <?= JLayoutHelper::render('b0.module', [
                    'title' => '',
                    'id' => $categoryParams->module_3,
                ]) ?>
            </div>
        </div>
    </div>
</section>
<?php endif;?>

<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-large-top">
    <p class="uk-h4 uk-text-warning">Напоминаем, что своевременное проведение технического обслуживания <?= $modelName ?> в специализированном сервисе с применением качественных
        расходных материалов увеличивает срок эксплуатации автомобиля до ремонта.
    </p>
</div>

<div class="uk-margin-large-top uk-hidden-small">
    <?= JLayoutHelper::render('b0.module', [
        'title' => '',
        'id' => $markupParams->get('main.module_minibanners'),
    ]) ?>
</div>

<?php if (in_array(3, $this->user->getAuthorisedViewLevels(), true)) { ?>
    <!-- Панель фильтров -->
    <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-top uk-margin-bottom">
        <form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm"
              id="adminForm" enctype="multipart/form-data">
            <!-- Вывод фильтров -->
            <fieldset data-uk-margin>
	            <?= isset($this->filters[MaintenanceKeys::KEY_MODEL]) ? $this->filters[MaintenanceKeys::KEY_MODEL]->onRenderFilter($this->section) : '' ?>
	            <?= isset($this->filters[MaintenanceKeys::KEY_YEAR]) ? $this->filters[MaintenanceKeys::KEY_YEAR]->onRenderFilter($this->section) : '' ?>
	            <?= isset($this->filters[MaintenanceKeys::KEY_MOTOR]) ? $this->filters[MaintenanceKeys::KEY_MOTOR]->onRenderFilter($this->section) : '' ?>
	            <?= isset($this->filters[MaintenanceKeys::KEY_MILEAGE]) ? $this->filters[MaintenanceKeys::KEY_MILEAGE]->onRenderFilter($this->section) : '' ?>
                <button class="uk-button" type="button" title="Применить выбранные фильтры"
                    onclick="Joomla.submitbutton('records.filters')">Поиск
                </button>
            </fieldset>
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
    <!-- Конец панели фильтров -->

    <!-- Панель меню -->
    <div class="uk-navbar uk-margin-top">
        <div class="uk-navbar-nav">
            <!-- Вывод состояния фильтров -->
            <?php if ($this->worns): ?>
                <?= JLayoutHelper::render('b0.filterStatus', ['worns' => $this->worns,]) ?>
            <?php endif; ?>
        </div>
        <div class="uk-navbar-flip">
            <ul class="uk-subnav uk-subnav-line">
                <!-- Добавить запись -->
                <?php if(!empty($this->postbuttons)) :
                    echo JLayoutHelper::render('b0.addItem', [
                        'postButtons' => $this->postbuttons,
                        'section' => $this->section,
                        'category' => $this->category,
                        'typeName' => 'Техобслуживание'
                    ]);
                endif; ?>
            </ul>
        </div>
    </div>
    <!-- Конец панели меню -->

    <!-- Вывод статей -->
    <?php if($this->items):?>
        <?= $this->loadTemplate('list_'.$this->list_template) ?>

        <?php if ($this->tmpl_params['list']->def('tmpl_core.item_pagination', 1)) : ?>
            <form method="post">
                <div style="text-align: center;">
                    <small>
                        <?php if($this->pagination->getPagesCounter()):?>
                            <?= $this->pagination->getPagesCounter() ?>
                        <?php endif;?>
                        <?php  if ($this->tmpl_params['list']->def('tmpl_core.item_limit_box', 0)) : ?>
                            <?= str_replace('<option value="0">Все</option>', '', $this->pagination->getLimitBox()) ?>
                        <?php endif; ?>
                        <?= $this->pagination->getResultsCounter() ?>
                    </small>
                </div>
                <?php if($this->pagination->getPagesLinks()): ?>
                    <div style="text-align: center;" class="pagination">
                        <?= str_replace('<ul>', '<ul class="pagination-list">', $this->pagination->getPagesLinks()) ?>
                    </div>
                    <div class="clearfix"></div>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    <?php elseif($this->worns):?>
        <h4 class="uk-text-center">По Вашему запросу ничего не найдено</h4>
    <?php endif;?>
<?php }?>

<?php
function renderThead($type)
{
    if ($type === 'benzin') {
	    echo '<tr>
            <th>Операция ТО</th>
            <th class="uk-text-center">15000 км<br>ТО-1</th>
            <th class="uk-text-center">30000 км<br>ТО-2</th>
            <th class="uk-text-center">45000 км<br>ТО-3</th>
            <th class="uk-text-center">60000 км<br>ТО-4</th>
            <th class="uk-text-center">75000 км<br>ТО-5</th>
            <th class="uk-text-center">90000 км<br>ТО-6</th>
        </tr>';
    }
    else {
	    echo '<tr>
            <th>Операция ТО</th>
            <th class="uk-text-center">15000 км<br>ТО-1</th>
            <th class="uk-text-center">30000 км<br>ТО-2</th>
            <th class="uk-text-center">45000 км<br>ТО-3</th>
            <th class="uk-text-center">60000 км<br>ТО-4</th>
            <th class="uk-text-center">75000 км<br>ТО-5</th>
            <th class="uk-text-center">90000 км<br>ТО-6</th>
        </tr>';
    }
}

function renderTbodyOperations($items)
{
	foreach ($items['operations'] as $operations){
		echo '<tr class="uk-table-middle">';
		foreach ($operations as $key => $operation) {
			if ($key === 'name') {
				echo '<td class="uk-text-left">';
			}
			else {
				echo '<td class="uk-text-center">';
			}
			echo $operation;
			echo '</td>';
		}
		echo '</tr>';
	}
}

function renderTbodyLinks($motorData)
{
	echo '<tr class="uk-table-middle">';
	echo '<td><strong>Стоимость ТО (<span class="uk-text-danger"> кликните на иконку</span> )</strong></td>';
	foreach ($motorData['links'] as $item) {
		$toNum = $item['milage'] / $motorData['freq'];
		$title = 'Техническое обслуживание '.$motorData['model'].' '.$motorData['motor'].' '.$motorData['years'].' пробег '.$item['milage']. ' км (ТО-'.$toNum.')';
		switch ($item['type']) {
			case 'oil':
				$imgAlt = 'Замена масла и воздушного фильтра';
				break;
			case 'oil-ign':
				$imgAlt = 'Замена масла, воздушного фильтра и свечей зажигания';
				break;
			case 'oil-ign-grm':
				$imgAlt = 'Замена масла, воздушного фильтра, свечей зажигания и комплекта ГРМ';
				break;
			case 'oil-ign-liq':
				$imgAlt = 'Замена масла, воздушного фильтра, свечей зажигания и технических жидкостей';
				break;
			case 'oil-ign-grm-liq':
				$imgAlt = 'Замена масла, воздушного фильтра, свечей зажигания, комплекта ГРМ и технических жидкостей';
				break;
			default:
				$imgAlt = '';
		}
		$imgSrc = '/images/elements/maintenance/'.$item['type'].'.png';
		
		echo '<td class="uk-text-center">';
		echo '<a href="'.$item['tdHref'].'" title="'.$title.'" target="_blank">';
		echo '<img src="'.$imgSrc.'" alt="'.$imgAlt.'" width="64" height="32">';
		echo '</a>';
		echo '</td>';
	}
	echo '</tr>';
}
