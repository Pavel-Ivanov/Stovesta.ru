<?php
defined('_JEXEC') or die();
JImport('b0.Kit.KitKeys');

$item = $this->item;
if ($item->meta_key){
    $this->document->setTitle($item->meta_key);
}
$this->document->setMetaData('keywords', '');
?>

<article class="uk-article">
    <?php if($item->controls) {
        echo JLayoutHelper::render('b0.controls-in-line', $item->controls);
    } ?>

	<h1><?= $item->title ?></h1>
    <hr class="uk-article-divider">
<!--	<?php /*if ($item->meta_descr) :*/?>
        <p class="uk-article-lead"><?php /*= $item->meta_descr */?></p>
	--><?php /*endif; */?>

	<?php if (isset($item->fields_by_key[KitKeys::KEY_ANNOUNCEMENT])) :?>
        <section id="announcement" class="uk-margin-large-top">
            <p><?= $item->fields_by_key[KitKeys::KEY_ANNOUNCEMENT]->result ?></p>
        </section>
	<?php endif; ?>

    <section id="spareparts" class="uk-margin-large-top">
		<?= $item->fields_by_key[KitKeys::KEY_SPAREPARTS_LIST]->result ?? '' ?>
	</section>
	<section id="accessories" class="uk-margin-large-top">
		<?= $item->fields_by_key[KitKeys::KEY_ACCESSORIES_LIST]->result ?? '' ?>
    </section>
	<section id="works" class="uk-margin-large-top">
		<?= $item->fields_by_key[KitKeys::KEY_WORKS_LIST]->result ?? '' ?>
    </section>

    <?php if (isset($item->fields_by_key[KitKeys::KEY_BODY])) :?>
        <section id="announcement" class="uk-margin-large-top">
            <p><?= $item->fields_by_key[KitKeys::KEY_BODY]->result ?></p>
        </section>
    <?php endif; ?>
</article>
