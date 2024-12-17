<?php
defined('_JEXEC') or die;
/**
 * @var array $list
 */

?>
<div class="uk-slidenav-position" data-uk-slideshow="{autoplay:true, autoplayInterval:5000, animation: 'scale'}">
	<ul class="uk-slideshow">
		<?php foreach ($list as $banner):?>
		<li>
			<img src="<?= $banner->params->get('imageurl') ?>" alt="<?= $banner->params->get('alt') ?>" width="970" height="300" />
			<div class="uk-overlay-panel uk-overlay-hover uk-flex uk-flex-right uk-flex-bottom uk-text-center">
				<a href="<?= substr($banner->clickurl, 6) ?>" class="uk-button uk-button-large uk-button-danger" target="_blank" rel="noopener noreferrer">
					Подробнее<i class="uk-icon-angle-right uk-icon-medium uk-margin-left"></i>
				</a>
			</div>
		</li>
		<?php endforeach;?>
	</ul>
	<a class="uk-slidenav uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
	<a class="uk-slidenav uk-slidenav-next" data-uk-slideshow-item="next"></a>
</div>
