<?php
/**
* @package   yoo_aurora
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'name' => 'widget/grid_aurora',

    'main' => 'YOOtheme\\Widgetkit\\Widget\\Widget',

    'config' => array(

        'name'  => 'grid_aurora',
        'label' => 'Grid Aurora',
        'icon'  => 'plugins/widgets/grid_aurora/widget.svg',
        'view'  => 'plugins/widgets/grid_aurora/views/widget.php',
        'item'  => array('title', 'content', 'media'),
        'settings' => array(
            'columns' => '1',
            'columns_small' => 0,
            'columns_medium' => 0,
            'columns_large' => 0,
            'columns_xlarge' => 0,
            'gutter' => 'default',
            'panel' => 'blank',
            'animation' => 'none',
            'text_align' => 'left',
            'title_size' => 'panel',
            'media_align' => 'teaser',
            'media_width' => '1-2',
            'media_breakpoint' => 'medium',
            'content_align' => true,
            'media_border' => 'none',
            'link_style' => 'button',
            'title' => true,
            'media' => true,
            'content' => true,
            'link' => true,
            'button_link' => true,
            'link_text' => 'Read more',
            'class' => ''
        )

    ),

    'events' => array(

        'init.site' => function($event, $app) {
        },

        'init.admin' => function($event, $app) {
            $app['angular']->addTemplate('grid_aurora.edit', 'plugins/widgets/grid_aurora/views/edit.php', true);
        }

    )

);
