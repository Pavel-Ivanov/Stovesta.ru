<?php
defined('_JEXEC') or die();

// Получаем параметры
$paramsMarkup = $this->tmpl_params['markup'];
$paramsList = $this->tmpl_params['list'];
$listOrder	= @$this->ordering;
$listDirn	= @$this->ordering_dir;
?>

<h1>Наборы запчастей</h1>
<hr class="uk-article-divider">

<!-- Панель фильтров -->
<div class="uk-panel uk-panel-box uk-panel-box-secondary">
    <form class="uk-form" method="post" action="<?= $this->action ?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
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
                    'typeName' => 'Набор'
                ]);
            endif; ?>
        </ul>
    </div>
</div>

<div class="uk-margin-large-top">
    <hr class="uk-article-divider">
	<?php if($this->items):?>
		<?= $this->loadTemplate('list_'.$this->list_template) ?>
        <hr class="uk-article-divider">
        <form method="post" class="uk-margin-bottom-remove">
            <div class="uk-text-center">
                <small>
                    <?php if($this->pagination->getPagesCounter()):?>
                        <?= '<span class="uk-margin-right">'. $this->pagination->getPagesCounter() . '</span>' ?>
                    <?php endif;?>
                    <?= str_replace(['<option value="0">'.JText::_('JALL').'</option>', '<option value="100">100</option>'], '', $this->pagination->getLimitBox()) ?>
                    <?= '<span class="uk-margin-left">'. $this->pagination->getResultsCounter() . '</span>' ?>
                </small>
            </div>
            <div class="uk-text-center pagination uk-margin-remove">
                <?= $this->pagination->getPagesLinks() ?>
            </div>
        </form>
    <?php endif;?>
</div>
