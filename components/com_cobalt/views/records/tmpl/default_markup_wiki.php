<?php
defined('_JEXEC') or die();

JImport('b0.Tags.Tags');

$app = JFactory::getApplication();

/** @var JRegistry $paramsMarkup */
$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsList */
$paramsList = $this->tmpl_params['list'];

$tags = new Tags($this->section->id, $this->worns);

$listOrder	= @$this->ordering;
$listDirn	= @$this->ordering_dir;

$this->document->setMetaData('description', $this->section->params['more']->metadesc . ($this->pagination->pagesCurrent > 1 ? ' Страница '.$this->pagination->pagesCurrent : ''));

switch ($listOrder) {
	case 'r.ctime':
		$textOrder = 'сначала новые';
		break;
	case 'r.hits':
		$textOrder = 'сначала популярные';
		break;
	case 'r.title':
		$textOrder = 'по алфавиту';
		break;
	default:
		$textOrder = 'сначала новые';
}
?>

<!--  Шапка раздела -->
<div class="uk-grid" data-uk-grid-margin>
    <!-- Иконка раздела -->
    <div class="uk-width-small-1-6">
        <img src="/images/icons-section/icon-wiki.png" alt="<?= $this->description ?>">
    </div>

    <div class="uk-width-small-5-6">
        <div class="uk-grid" data-uk-grid-margin>
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

<!-- Панель меню -->
<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom">
    <?php $tags->tagsRenderAsBage();?>

    <hr class="uk-article-divider">

    <nav class="uk-navbar uk-margin-top">
        <div class="uk-navbar-nav">
            <form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <div class="uk-form-row">
                    <input type="text" id="form-h-it" class="uk-form-width-medium uk-form-danger"
                           placeholder="Я ищу" name="filter_search"
                           value="<?= htmlentities($this->state->get('records.search'), ENT_COMPAT, 'utf-8') ?>"/>
                    <button class="uk-button uk-button-success" type="button" title="Применить фильтры"
                            onclick="Joomla.submitbutton('records.filters')">Показать <i class="uk-icon-check uk-icon-small"></i>
                    </button>
                    <button class="uk-button uk-button-primary" type="button" title="Сбросить фильтры"
                            onclick="Joomla.submitbutton('records.cleanall')">Сбросить <i class="uk-icon-close uk-icon-small"></i>
                    </button>
                </div>

                <input type="hidden" name="section_id" value="<?= $this->state->get('records.section_id')?>">
                <input type="hidden" name="cat_id" value="<?= $app->input->getInt('cat_id') ?>">
                <input type="hidden" name="option" value="com_cobalt">
                <input type="hidden" name="task" value="">
                <input type="hidden" name="limitstart" value="0">
                <input type="hidden" name="filter_order" value="<?= $this->ordering; ?>">
                <input type="hidden" name="filter_order_Dir" value="<?= $this->ordering_dir; ?>">
                <?= JHtml::_( 'form.token' ) ?>
                <?php if($this->worns):?>
                    <?php foreach ($this->worns as $worn):?>
                        <input type="hidden" name="clean[<?= $worn->name ?>]" id="<?= $worn->name ?>" value="">
                    <?php endforeach;?>
                <?php endif;?>
            </form>
        </div>
        <div class="uk-navbar-flip">
            <ul class="uk-subnav uk-subnav-line">
                <!-- Добавить статью -->
                <?php if(!empty($this->postbuttons)) :
                    echo JLayoutHelper::render('b0.addItems', [
                        'postButtons' => $this->postbuttons,
                        'section' => $this->section,
                        'category' => $this->category,
                    ]);
                endif; ?>
                <!-- Сортировка -->
                <li data-uk-dropdown="{mode:'click'}">
                    Сортировка: <a href="#"><?= $textOrder ?><i class="uk-icon-caret-down uk-margin-left"></i></a>
                    <div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><a href="#" onclick="Joomla.tableOrdering('r.ctime','desc','')">сначала новые</a></li>
                            <li><a href="#" onclick="Joomla.tableOrdering('r.hits','desc','')">сначала популярные</a></li>
                            <li><a href="#" onclick="Joomla.tableOrdering('r.title','asc','')">по алфавиту</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<hr class="uk-article-divider">

<?php if($this->items):?>
	<?= $this->loadTemplate('list_'.$this->list_template) ?>
    <hr class="uk-article-divider">
    <?= JLayoutHelper::render('b0.pagination', $this->pagination); ?>
<?php elseif($this->worns):?>
	<h4 class="uk-text-center">К сожалению, по Вашему запросу ничего не найдено. Попробуйте:</h4>
	<?php if (isset($this->worns['search'])):?>
		<h4 class="uk-text-center">- изменить поисковую фразу</h4>
	<?php endif;?>
<?php endif;?>
