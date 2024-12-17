<?php
defined('_JEXEC') or die();
JImport('b0.fixtures');

$doc = JFactory::getDocument();
$siteName = JFactory::getApplication()->get('sitename');

$markupParams = $this->tmpl_params['markup'];
$listParams = $this->tmpl_params['list'];

$title = $this->section->description;
$description = $this->section->params['more']->metadesc;

$doc->setTitle($description . ' недорого в ' . $siteName);
?>

<!--  Шапка раздела -->
<div class="uk-grid" data-uk-grid-margin>
    <!-- Иконка раздела -->
    <div class="uk-width-medium-1-6">
        <img src="/<?= $markupParams['main']->section_icon ?>" width="135" height="135" alt="<?= $this->description ?>">
    </div>

    <div class="uk-width-medium-5-6">
        <div class="uk-grid" data-uk-grid-margin>
            <!-- Заголовок раздела -->
            <div class="uk-width-medium-1-1">
                <h1>
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

<!-- Вывод индекса категории -->
<?= $this->loadTemplate('cindex_'.$this->section->params->get('general.tmpl_category')) ?>
