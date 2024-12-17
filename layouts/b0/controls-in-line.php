<?php
defined('JPATH_BASE') or die();
/**
 * @var array $displayData
 */
?>
<?php if ($displayData) : ?>
    <nav class="uk-float-right">
        <ul class="uk-subnav uk-subnav-line">
            <?php foreach($displayData as $key => $link) {
                echo "<li>{$link}</li>";
            } ?>
        </ul>
    </nav>
<?php endif; ?>
