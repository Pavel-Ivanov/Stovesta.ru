<?php
/**
* @package   yoo_aurora
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// Grid
if ($settings['gutter'] == 'none') {
    $grid = 'uk-clearfix uk-flex';
} else {
    $grid = 'uk-grid';
    $grid .= ($settings['gutter'] == 'small') ? ' uk-grid-small' : '' ;
}

$grid .= ' uk-grid-width-1-'.$settings['columns'];
$grid .= $settings['columns_small'] ? ' uk-grid-width-small-1-'.$settings['columns_small'] : '';
$grid .= $settings['columns_medium'] ? ' uk-grid-width-medium-1-'.$settings['columns_medium'] : '';
$grid .= $settings['columns_large'] ? ' uk-grid-width-large-1-'.$settings['columns_large'] : '';
$grid .= $settings['columns_xlarge'] ? ' uk-grid-width-xlarge-1-'.$settings['columns_xlarge'] : '';

// Panel
$panel = 'uk-panel';
switch ($settings['panel']) {
    case 'box' :
        $panel .= ' uk-panel-box';
        break;
    case 'primary' :
        $panel .= ' uk-panel-box uk-panel-box-primary';
        break;
    case 'secondary' :
        $panel .= ' uk-panel-box uk-panel-box-secondary';
        break;
    case 'header' :
        $panel .= ' uk-panel-header';
        break;
    case 'space' :
        $panel .= ' uk-panel-space';
        break;
}

// Media Align
switch ($settings['media_align']) {
    case 'teaser':
        $media_align = 'uk-panel-teaser uk-margin-bottom';
        break;
    case 'top':
    case 'bottom':
        $media_align = 'uk-text-center';
        break;
    case 'left':
        $media_align = 'uk-align-medium-left uk-text-center';
        break;
    case 'right':
        $media_align = 'uk-align-medium-right uk-text-center';
        break;
}

// Media Width
$media_width = 'uk-width-' . $settings['media_breakpoint'] . '-' . $settings['media_width'];

switch ($settings['media_width']) {
    case '1-5':
        $content_width = '4-5';
        break;
    case '1-4':
        $content_width = '3-4';
        break;
    case '3-10':
        $content_width = '7-10';
        break;
    case '1-3':
        $content_width = '2-3';
        break;
    case '2-5':
        $content_width = '3-5';
        break;
    case '1-2':
    default:
        $content_width = '1-2';
        break;
}

$content_width = 'uk-width-medium-' . $content_width;

// Content Align
$content_align  = $settings['content_align'] ? 'uk-flex-middle' : '';

// Title Size
switch ($settings['title_size']) {
    case 'h1':
    case 'h2':
    case 'h3':
    case 'h4':
        $title_size = 'uk-' . $settings['title_size'] . ' uk-margin-top-remove';
        break;
    case 'large':
        $title_size = 'uk-heading-large uk-margin-top-remove';
        break;
    default:
        $title_size = 'uk-panel-title';
        break;
}

// Link Style
switch ($settings['link_style']) {
    case 'button':
        $link = 'uk-button';
        break;
    case 'primary':
        $link = 'uk-button uk-button-primary';
        break;
    case 'button-large':
        $link = 'uk-button uk-button-large';
        break;
    case 'primary-large':
        $link = 'uk-button uk-button-large uk-button-primary';
        break;
    default:
        $link = '';
        break;
}

?>

<div class="<?php echo $grid; ?> uk-grid-match uk-text-<?php echo $settings['text_align']; ?> <?php echo $settings['class']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>

<?php foreach ($items as $i => $item) :

        // Media Type
        $attrs = array();
        $class  = '';

        if ($item->type('media') == 'image') {
            $class .= ($settings['media_border'] != 'none') ? 'uk-border-' . $settings['media_border'] . ' ' : '';
            $attrs['alt'] = $item['title'];
        }

        if ($item->type('media') == 'video') {
            $class .= 'uk-responsive-width ';
            $attrs['controls'] = '';
        }

        if ($item['width']) {
            $attrs['width'] = $item['width'];
        }

        if ($item['height']) {
            $attrs['height'] = $item['height'];
        }

        if ($class) {
            $attrs['class'] = $class;
        }

        $media = $item->media('media', $attrs);

        if ($item['link'] && $settings['link']) {
            $medialinked = '<a href="' . $item['link'] . '">' . $media . '</a>';
        } else {
            $medialinked = $media;
        }

        // Animation
        $delay = $i * 300;
        $animation = ($settings['animation'] != 'none') ? 'data-uk-scrollspy="{cls:\'uk-animation-' . $settings['animation'] . '\', delay:' . $delay . '}"' : '';

    ?>

    <div<?php if ($settings['gutter'] == 'none') echo ' class="uk-float-left uk-flex-item-none"'; ?>>
        <div class="<?php echo $panel; ?>" <?php echo $animation; ?>>

            <?php if ($item['media'] && $settings['media'] && in_array($settings['media_align'], array('teaser', 'top'))) : ?>
                <?php if (isset($item['link']) && $settings['link']) echo '<a href="' . $item['link'] . '">'; ?>
                <figure class="tm-caption uk-text-center">
                    <?php echo $media; ?>
                    <figcaption class="uk-flex uk-flex-middle uk-flex-center">
                        <h3 class="uk-h4"><?php echo $item['title']; ?></h3>
                    </figcaption>
                </figure>
                <?php if (isset($item['link']) && $settings['link']) echo '</a>'; ?>
            <?php endif; ?>

            <?php if ($item['media'] && $settings['media'] && in_array($settings['media_align'], array('left', 'right'))) : ?>
            <div class="uk-grid <?php echo $content_align; ?>" data-uk-grid-margin>
                <div class="<?php echo $media_width ?><?php if ($settings['media_align'] == 'right') echo ' uk-float-right uk-flex-order-last-' . $settings['media_breakpoint'] ?>">
                    <?php echo $medialinked; ?>
                </div>
                <div class="<?php echo $content_width ?>">
                    <div class="uk-panel">
            <?php endif; ?>

            <?php if ($item['title'] && $settings['title']) : ?>
            <h3 class="<?php echo $title_size; ?>"><?php echo $item['title']; ?></h3>
            <?php endif; ?>

            <?php if ($item['media'] && $settings['media'] && $settings['media_align'] == 'bottom') : ?>
            <p class="<?php echo $media_align; ?>"><?php echo $medialinked; ?></p>
            <?php endif; ?>

            <?php if ($item['content'] && $settings['content']) : ?>
            <div class="uk-margin"><?php echo $item['content']; ?></div>
            <?php endif; ?>

            <?php if ($item['link'] && $settings['link'] && $settings['button_link']) : ?>
            <p><a<?php if($link) echo ' class="' . $link . '"' ?> href="<?php echo $item['link']; ?>"><?php echo $settings['link_text']; ?></a></p>
            <?php endif; ?>

            <?php if ($item['media'] && $settings['media'] && in_array($settings['media_align'], array('left', 'right'))) : ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

<?php endforeach; ?>

</div>
