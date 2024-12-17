<?php
defined('_JEXEC') or die();
if(!$this->items) {
	return;
}
//JImport('b0.Wiki.WikiIds');
JImport('b0.Wiki.Wikies');

/** @var JRegistry $params */
$paramsList = $this->tmpl_params['list'];
$articles = new Wikies($this->items, $paramsList);
//b0debug($articles);
?>

<ul class="uk-grid uk-grid-width-medium-1-2 uk-grid-match" data-uk-grid-margin data-uk-grid-match="{target:'.uk-panel'}">
	<?php foreach ($articles->items as $article):?>
        <li>
            <div class="uk-panel uk-panel-box">
                <div class="b0-title-related">
                    <a href="<?= $article->url ?>" title="<?= $article->title?>" target="_blank">
			            <?= $article->title?>
                    </a>
                </div>
                <hr class="uk-article-divider">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-3">
                        <?= $article->image ?>
                    </div>
                    <div class="uk-width-2-3">
                        <?= $article->announcement ?>
                    </div>
                </div>
            </div>
        </li>
	<?php endforeach;?>
</ul>
