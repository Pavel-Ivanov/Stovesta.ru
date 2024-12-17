<?php
defined('_JEXEC') or die();
/** @var JRegistry $paramsRecord */

JImport('b0.Post.Post');
JImport('b0.Company.CompanyConfig');

$doc = JFactory::getDocument();
//unset($doc->_scripts['/media/jui/js/jquery.min.js']);
unset($doc->_scripts['/media/jui/js/jquery-noconflict.js']);
unset($doc->_scripts['/media/jui/js/jquery-migrate.min.js']);
unset($doc->_scripts['/media/jui/js/bootstrap.min.js']);
unset($doc->_scripts['/media/system/js/mootools-core.js']);
unset($doc->_scripts['/media/system/js/core.js']);
unset($doc->_scripts['/media/system/js/mootools-more.js']);
unset($doc->_scripts['/media/system/js/modal.js']);
unset($doc->_scripts['/components/com_cobalt/library/js/felixrating.js']);
unset($doc->_scripts['/news?task=ajax.mainJS']);
unset($doc->_scripts['text/javascript']);
unset($doc->_styleSheets['/media/system/css/modal.css']);
unset($doc->_styleSheets['/components/com_cobalt/library/css/style.css']);
unset($doc->_styleSheets['/components/com_jce/editor/tiny_mce/plugins/columns/css/content.css']);
unset($doc->_styleSheets['/plugins/system/jce/css/content.css']);

$post = new Post($this->item, $this->user);
$this->document->setTitle($post->metaTitle);
$this->document->setMetaData('description', $post->metaDescription);
$this->document->setMetaData('keywords', $post->metaKey);
$this->document->setMetaData('generator', '');
JLayoutHelper::render('b0.openGraph', ['og' => $post->openGraph, 'doc' => $this->document]);

$paramsRecord = $this->tmpl_params['record'];
?>

<article class="uk-article" itemscope itemtype="https://schema.org/NewsArticle">
    <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
        <meta itemprop="url" content="<?= $post->image['url'] ?>" />
        <meta itemprop="image" content="<?= $post->image['url'] ?>" />
        <meta itemprop="height" content="<?= $post->image['height'] ?>px" />
        <meta itemprop="width" content="<?= $post->image['width'] ?>px" />
    </div>
    <meta itemprop="description" content="<?= $post->metaDescription ?>" />
    <meta itemprop="keywords" content="<?= $post->metaKey ?>"/>
    <meta itemprop="about" content="<?= $post->announcement ?>" />
    <meta itemprop="dateCreated" content="<?= $post->cTime ?>" />
    <meta itemprop="dateModified" content="<?= $post->mTime ?>"/>
    <meta itemprop="datePublished" content="<?= $post->cTime ?>" />
    <div itemprop="author"  itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="<?= CompanyConfig::COMPANY_NAME ?>" />
        <meta itemprop="address" content="<?= CompanyConfig::COMPANY_ADDRESS ?>" />
        <meta itemprop="telephone" content="<?= CompanyConfig::COMPANY_TELEPHONE ?>" />
        <meta itemprop="url" content="https://logan-shop.spb.ru" />
        <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
            <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
        </div>
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
            <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
        </div>
    </div>
    <div itemprop="publisher"  itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="<?= CompanyConfig::COMPANY_NAME ?>" />
        <meta itemprop="address" content="<?= CompanyConfig::COMPANY_ADDRESS ?>" />
        <meta itemprop="telephone" content="<?= CompanyConfig::COMPANY_TELEPHONE ?>" />
        <meta itemprop="url" content="https://logan-shop.spb.ru" />
        <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
            <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
        </div>
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
            <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
        </div>
    </div>
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?= $post->url ?>"/>

    <?php if($post->controls){
        echo JLayoutHelper::render('b0.controls-in-line', $post->controls);
    }?>

    <header itemprop="headline">
        <?php $post->renderTitle();?>
    </header>
	<hr class="uk-article-divider">
	<p class="uk-article-meta">
		<?php $post->renderCTime();?>
	</p>
    <div itemprop="articleBody">
	    <?php $post->renderImage();?>
	    <?php $post->renderBody();?>
    </div>
    <div class="uk-clearfix"></div>

	<?php $post->renderGallery();?>
	<?php $post->renderVideo();?>

    <hr class="uk-article-divider">
    <div>
        <?= JLayoutHelper::render('b0.module', [
            'title' => '',
            'id' => $paramsRecord->get('tmpl_core.module_minibanners'),
        ]) ?>
    </div>

    <footer class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-large-top">
        <div class="uk-flex uk-flex-middle uk-flex-space-around uk-flex-wrap">
            <div class="uk-text-small">
                <?php $post->renderHits();?>
            </div>
            <div>
                <?= JLayoutHelper::render('b0.discuss', ['href' => CompanyConfig::COMPANY_VK_LINK, 'src' => CompanyConfig::COMPANY_VK_ICON]) ?>
            </div>
        </div>
    </footer>
</article>
