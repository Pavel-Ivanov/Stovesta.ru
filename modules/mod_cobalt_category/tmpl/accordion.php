<?php
 defined('_JEXEC') or die('Restricted access'); ?>

<div class="uk-accordion" data-uk-accordion="{showfirst:false}">
    <?php foreach ($categories as $cat) :?>
        <p class="uk-accordion-title uk-h2" style="margin-top: 0">
            <?= $cat->title;?>
            <i class="uk-icon-angle-down uk-float-right"></i>
        </p>
        <?php //var_dump($cat);?>
        <?php if($cat->children):?>
            <div class="uk-accordion-content">
                <?php $model = JModelLegacy::getInstance('Categories', 'cobaltModel');
                $model->section = $params->get('section_id');
                $model->parent_id = $cat->id;
                $model->order = $params->get('order') == 1 ? 'c.lft ASC' : 'c.title ASC';
                $model->levels = $cat->level + 1;
                $model->all = 0;
                //$model->nums = $params->get('cat_nums', 'current');
                $model->nums = 'current';
                $list = $model->getItems();
                if(!$list) return;?>

                <ul class="uk-list">
                    <?php foreach($list as $sub_cat ) :
                        if (!$params->get('cat_empty', 1) && !$sub_cat->records_num) continue;?>
                        <li>
                            <a href="<?= JRoute::_($sub_cat->link)?>">
                                <?= $sub_cat->title;?>
                                <?php if($params->get('cat_nums', 0) && $sub_cat->params->get('submission')):?>
                                    <span class="small">(<?= (int)$sub_cat->records_num; ?>)</span>
                                <?php endif;?>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
    <?php endforeach;?>
</div>
