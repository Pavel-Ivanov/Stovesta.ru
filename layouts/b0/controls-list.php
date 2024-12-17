<?php
defined('_JEXEC') or die();
/**
 * @var array $displayData
 */
?>

<nav class="uk-float-right uk-hidden">
        <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
            <button class="uk-button-link">
                <i class="uk-icon-cogs uk-icon-small"></i>
            </button>
            <div class="uk-dropdown uk-dropdown-small">
                <ul class="uk-nav uk-nav-dropdown uk-panel uk-panel-box uk-panel-box-secondary">
                    <?//= renderControls($displayData);?>
                    <?php
                    foreach($displayData as $key => $link) {
	                    if(is_array($link)) {
		                    echo '<li>' . $key;
		                    echo '<ul class="dropdown-menu">';
		                    //$out .= renderControls($link);
		                    echo '</ul>';
		                    echo '</li>';
	                    }
	                    else {
		                    echo "<li>{$link}</li>";
	                    }
                    }
                    ?>
                </ul>
            </div>
        </div>
</nav>
