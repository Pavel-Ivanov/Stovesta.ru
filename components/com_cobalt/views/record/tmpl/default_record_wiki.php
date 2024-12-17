<?php
defined('_JEXEC') or die();

JImport('b0.Wiki.Wiki');
JImport('b0.Company.CompanyConfig');
JImport('b0.pricehelper');
//JImport('b0.fixtures');

$doc = JFactory::getDocument();
//b0dd($doc->_scripts);
unset($doc->_generator);
//unset($doc->_scripts['/media/jui/js/jquery.min.js']);
unset($doc->_scripts['/media/jui/js/jquery-noconflict.js']);
unset($doc->_scripts['/media/jui/js/jquery-migrate.min.js']);
unset($doc->_scripts['/media/jui/js/bootstrap.min.js']);
unset($doc->_scripts['/media/system/js/mootools-core.js']);
unset($doc->_scripts['/media/system/js/mootools-more.js']);
unset($doc->_scripts['/media/system/js/core.js']);
unset($doc->_scripts['/media/system/js/modal.js']);
unset($doc->_scripts['/component/cobalt/?task=ajax.mainJS&Itemid=1']);
unset($doc->_script['text/javascript']);
//b0debug($doc->_scripts);
unset($doc->_styleSheets['/media/system/css/modal.css']);
unset($doc->_styleSheets['/components/com_cobalt/library/css/style.css']);
unset($doc->_styleSheets['/components/com_jce/editor/tiny_mce/plugins/columns/css/content.css']);
unset($doc->_styleSheets['/plugins/system/jce/css/content.css']);
//b0debug($doc->_styleSheets);

$wiki = new Wiki($this->item, $this->user);
$this->document->setTitle($wiki->metaTitle);
$this->document->setMetaData('description', $wiki->metaDescription);
JLayoutHelper::render('b0.openGraph', ['og' => $wiki->openGraph, 'doc' => $this->document]);

$item = $this->item;
/** @var JRegistry $paramsRecord */
$paramsRecord = $this->tmpl_params['record'];
?>

<article class="uk-article" itemscope itemtype="https://schema.org/TechArticle">
	<?php if($wiki->controls){
		echo JLayoutHelper::render('b0.controls-in-line', $wiki->controls);
	}?>
	<?php if ($wiki->metaDescription) {
		echo '<meta itemprop="description" content="' . $wiki->metaDescription . '" />';
	}?>
    <meta itemprop="about" content="<?= $wiki->announcement ?>" />
    <meta itemprop="dateCreated" content="<?= $wiki->cTime ?>" />
	<?php if ($wiki->mTime) {
		echo '<meta itemprop="dateModified" content="' . $wiki->mTime . '"/>';
	}?>
    <meta itemprop="datePublished" content="<?= $wiki->cTime ?>" />
    <div itemprop="publisher"  itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="<?= CompanyConfig::COMPANY_NAME ?>" />
        <meta itemprop="address" content="<?= CompanyConfig::COMPANY_ADDRESS ?>" />
        <meta itemprop="telephone" content="<?= CompanyConfig::COMPANY_TELEPHONE ?>" />
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
            <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
        </div>
    </div>
    <div itemprop="author" itemscope itemtype="https://schema.org/Person">
        <meta itemprop="name" content="<?= $wiki->author ?>">
    </div>
    <meta itemscope itemprop="mainEntityOfPage" content="" itemType="https://schema.org/WebPage" itemid="<?= $wiki->url?>">

    <header>
        <h1 itemprop="headline">
			<?= $wiki->title?>
        </h1>
    </header>

    <hr class="uk-article-divider">
	<?php $wiki->renderContents();?>
    <section itemprop="articleBody" class="uk-margin-large-top">
		<?php $wiki->renderImage();?>
		<?php $wiki->renderBody();?>
    </section>

<!--    <hr class="uk-article-divider">
    <strong>Автор: </strong><?/*= ($wiki->author) */?>
	--><?php /*if (!empty($wiki->site)) {
		echo '<strong> на:</strong>' . $wiki->site;
	} */?>
	
	<?php $wiki->renderGallery();?>
	<?php $wiki->renderVideo();?>
	
	<?php
	if (!empty($wiki->goods)) :?>
        <hr class="uk-article-divider">
        <h2>Об этом писалось в статье</h2>
		<?php $wiki->renderGoods();?>
	<?php endif;?>

	<?= JLayoutHelper::render('b0.tabs', $wiki->tabs)?>

    <div class="uk-margin-large-top">
        <?= JLayoutHelper::render('b0.module', [
            'title' => '',
            'id' => $paramsRecord->get('tmpl_core.module_mini_banners'),
        ]) ?>

    </div>
    <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-top">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div>
                <ul class="uk-list uk-text-small">
                    <li>
                        <i class="uk-icon-calendar"></i>
                        <em> Дата: </em><?= $wiki->cTime->format($paramsRecord->get('tmpl_params.item_time_format')) ?>
                    </li>
                </ul>
            </div>
            <div class="uk-text-small">
				<?= JLayoutHelper::render('b0.hits', ['hits' => $wiki->hits])?>
            </div>
            <div>
				<?= JLayoutHelper::render('b0.discuss', ['href' => CompanyConfig::COMPANY_VK_LINK, 'src' => CompanyConfig::COMPANY_VK_ICON])?>
            </div>
        </div>
    </div>
</article>
