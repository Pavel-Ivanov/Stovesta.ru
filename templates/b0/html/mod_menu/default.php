<?php
defined('_JEXEC') or die;
JImport('b0.fixtures');
/** @var JRegistry $params */
/** @var array $list */
/** @var array $path */
/** @var string $default_id */
/** @var string $active_id */

$id = '';

if ($tagId = $params->get('tag_id', '')) {
	$id = ' id="' . $tagId . '"';
}

// The menu class is deprecated. Use nav instead
//b0debug($active_id);
?>

<nav class="tm-sidebar-nav">
    <ul class="uk-navbar-nav uk-hidden-small"<?php echo $id; ?>>
        <?php foreach ($list as $i => &$item) {
            $class = '';
        
            if ($item->id == $default_id) {
                $class .= ' default';
            }
            if ($item->id == $active_id) {
                $class .= ' current';
            }
            if (in_array($item->id, $path)) {
                $class .= ' active';
            }
            if ($item->type === 'separator') {
                $class .= ' divider';
            }
/*            if ($item->deeper) {
                $class .= ' deeper';
            }*/
            if ($item->parent) {
                $class .= 'uk-parent';
            }
            echo '<li class="' . $class . '">';
        
            switch ($item->type) :
                case 'separator':
                case 'component':
                case 'heading':
                case 'url':
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                    break;
        
                default:
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                    break;
            endswitch;
        
            // The next item is deeper.
            if ($item->deeper) {
                echo '<ul class="uk-nav uk-nav-navbar">';
            }
            // The next item is shallower.
            elseif ($item->shallower) {
                echo '</li>';
                echo str_repeat('</ul></li>', $item->level_diff);
            }
            // The next item is on the same level.
            else {
                echo '</li>';
            }
        } ?>
    </ul>
</nav>
