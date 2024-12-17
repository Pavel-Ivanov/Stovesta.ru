<?php
defined('_JEXEC') or die();
/**
 * @var array $displayData
 */
if (!$displayData) {
    return;
}
?>

<div class="uk-margin uk-width-medium-1-1">
	<!-- Заголовки закладок -->
	<ul class="uk-tab" data-uk-tab="{connect:'#tab-content'}">
		<?php
	    foreach ($displayData as $key => $tab ) {
		    echo '<li>';
		    echo '<a href="">' . $tab['title'] . ' ('.$tab['total'] .') '.'</a>';
		    echo '</li>';
	    }?>
	</ul>
	<!-- Контент закладок -->
	<ul class="uk-switcher uk-margin" id="tab-content">
		<?php
	    foreach ($displayData as $key => $tab ) {
		    echo '<li class="">' . $tab['result'] . '</li>';
	    }?>
	</ul>
</div>
