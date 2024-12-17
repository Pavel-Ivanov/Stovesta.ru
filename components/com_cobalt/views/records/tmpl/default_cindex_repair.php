<?php
defined('_JEXEC') or die();

$params = $this->tmpl_params['cindex'];
$parent_id = ($params->get('tmpl_params.cat_type', 2) == 1 && $this->category->id) ? $this->category->id : 1;

$cats_model = $this->models['categories'];
$cats_model->section = $this->section;
$cats_model->parent_id = $parent_id;
$cats_model->order = $params->get('tmpl_params.cat_ordering', 'c.lft ASC');
$cats_model->levels = $params->get('tmpl_params.subcat_level');
$cats_model->all = 0;
$cats_model->nums = ($params->get('tmpl_params.cat_nums') || $params->get('tmpl_params.subcat_nums') || !$params->get('tmpl_params.cat_empty', 1));
$categories = $cats_model->getItems();
$cats = [];

foreach ($categories as $cat) {
	if ($params->get('tmpl_params.cat_empty', 1) || ( !$params->get('tmpl_params.cat_empty', 1) && ($cat->num_current || $cat->num_all) ) ) {
		$cats[] = $cat;
	}
}
if(!count($cats)) {
	return;
}
$level = $this->category->level ?? 0;
?>

<ul class="uk-grid uk-text-center uk-margin-top" data-uk-grid-margin>
    <?php foreach ($cats as $cat) : ?>
        <li class="uk-width-medium-1-4">
            <a href="<?= JRoute::_($cat->link)?>">
                <img src="<?= $cat->image ?>" alt="<?= $cat->title ?>">
                <h2 class="uk-article-title">
					<?= $cat->title ?>
                </h2>
            </a>
        </li>
	<?php endforeach; ?>
</ul>
