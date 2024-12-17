<?php
defined('JPATH_BASE') or die;
/** @var array $displayData */
?>

<nav class="uk-float-right">
        <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
            <button class="uk-button-link">
                <i class="uk-icon-cogs uk-icon-small"></i>
            </button>
            <div class="uk-dropdown uk-dropdown-small">
                <ul class="uk-nav uk-nav-dropdown uk-panel uk-panel-box uk-panel-box-secondary">
                    <?php
                    foreach($displayData as $key => $link) {
	                    if(is_array($link)) {
		                    echo '<li>' . $key;
		                    echo '<ul class="dropdown-menu">';
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
