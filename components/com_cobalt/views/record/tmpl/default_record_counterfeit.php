<?php
defined('_JEXEC') or die();
$item = $this->item;
$fields = $this->item->fields_by_id;
$params = $this->tmpl_params['record'];
// Удаляем каноническую ссылку
foreach($this->document->_links as $lk => $dl) {
	if($dl['relation'] == 'canonical') {
		unset($this->document->_links[$lk]);
	}
}
?>

<article class="uk-article">
        <div class="uk-button-group uk-float-right">
            <?php if($this->user->get('id')):?>
                <?php if($item->controls):?>
                    <div class="uk-float-right">
                        <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                            <button class="uk-button-link">
                                <i class="uk-icon-cogs uk-icon-small"></i>
                            </button>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown uk-panel uk-panel-box uk-panel-box-secondary">
                                    <?= list_controls($item->controls);?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            <?php endif;?>
        </div>
    <h1>
        <?= $item->title?>
    </h1>
    <hr class="uk-article-divider">
    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-2-5 uk-text-center">
            <div class="uk-cover">
<!--                <iframe src="https://www.youtube.com/embed/to8087bDyBM" width="400" height="300" allowfullscreen></iframe>-->
                <iframe src="<?= $fields[246]->raw; ?>" width="400" height="300" allowfullscreen></iframe>
            </div>
        </div>
        <div class="uk-width-medium-3-5">
            <?= $fields[240]->result; ?>
        </div>
    </div>
    
    <?php if (isset($fields[241]->raw)){
        echo $fields[241]->result;
    }?>

    <?php if (isset($fields[242]->raw)) :?>
        <div class="uk-panel uk-panel-box uk-margin-large-top uk-margin-bottom">
            <h2>Статьи по теме</h2>
        </div>
        <?= $fields[242]->result; ?>
    <?php endif;?>

    <?php if (isset($fields[243]->value['link'])) :?>
        <div class="uk-panel uk-panel-box uk-margin-large-top">
            <h2>Видео по теме</h2>
        </div>
        <ul class="uk-grid uk-text-center uk-margin-top" data-uk-grid-margin>
            <?php foreach ($fields[243]->value['link'] as $link) : ?>
                <li class="uk-width-medium-1-2">
                    <iframe src="<?= $link ?>" width="490" height="330" allowfullscreen></iframe>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif;?>
</article>