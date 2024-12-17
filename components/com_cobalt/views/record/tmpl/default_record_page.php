<?php
defined('_JEXEC') or die();

/** @var JRegistry $paramsRecord */
//$paramsRecord = $this->tmpl_params['record'];
// Заменяем каноническую ссылку
$canon = [];
foreach($this->document->_links as $lk => $dl) {
	if($dl['relation'] === 'canonical') {
	    $canon = $dl;
		unset($this->document->_links[$lk]);
		break;
	}
}
if (!empty($this->tmpl_params['record']->get('tmpl_core.link_canonical'))){
	$this->document->_links[JUri::base(). $this->tmpl_params['record']->get('tmpl_core.link_canonical')] = $canon;
}
?>

<article class="uk-article">
    <?php if($this->item->controls) {
        echo JLayoutHelper::render('b0.controls', $this->item->controls);
    }?>
    <h1>
        <?= $this->item->title?>
    </h1>
    <hr class="uk-article-divider">
    <?= $this->item->fields_by_id[12]->result ?>
</article>
