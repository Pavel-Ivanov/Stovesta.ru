<?php
defined('_JEXEC') or die;
//JImport('b0.fixtures');
?>
<div class="uk-grid uk-grid-collapse uk-text-center" data-uk-grid-margin>
	<?php foreach ($list as $banner):?>
        <div class="uk-width-1-5">
            <figure class="uk-overlay uk-overlay-hover">
                <img src="<?= $banner->params->get('imageurl') ?>" alt="<?= $banner->params->get('alt'); ?>" width="160" height="120" />
                <div class="uk-overlay-panel uk-overlay-slide-top uk-overlay-background"><?= $banner->description ?></div>
                <a class="uk-position-cover" href="<?= substr($banner->clickurl, 6) ?>" target="_blank" rel="noopener noreferrer"></a>
            </figure>
        </div>
	<?php endforeach;?>
</div>
