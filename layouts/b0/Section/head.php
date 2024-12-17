<?php
defined('JPATH_BASE') or die();
/**
 * @var array $displayData
 */
?>
<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-2-10 uk-text-center">
        <img src="/<?= $displayData['section']->imageSource ?>" alt="">
    </div>
    <div class="uk-width-medium-8-10 uk-text-center-small">
        <div class="uk-grid">
            <!-- Заголовок раздела -->
            <div class="uk-width-medium-1-1 uk-margin-top">
                <h1>
                    <?= $displayData['section']->title ?>
                </h1>
            </div>
            <!-- Описание раздела -->
            <div class="uk-width-medium-1-1 uk-hidden-small">
                <hr class="uk-article-divider">
                <p class="ls-sub-title">
                    <?= $displayData['section']->subTitle ?>
                </p>
            </div>
        </div>
    </div>
</div>
