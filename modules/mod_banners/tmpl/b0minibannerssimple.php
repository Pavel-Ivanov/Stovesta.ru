<?php
defined('_JEXEC') or die();
/**
 * @var array $list
 */
?>
<ul class="uk-grid uk-text-center" data-uk-grid-margin>
    <?php foreach ($list as $banner):?>
        <li class="uk-width-medium-1-5">
            <a href="<?= substr($banner->clickurl, 6) ?>" target="_blank">
                <img src="<?= $banner->params->get('imageurl') ?>" alt="<?= $banner->params->get('alt') ?>" width="160" height="120" />
            </a>
        </li>
	<?php endforeach;?>
</ul>
