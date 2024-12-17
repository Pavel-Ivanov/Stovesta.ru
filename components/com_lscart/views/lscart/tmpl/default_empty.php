<?php
defined('_JEXEC') or die();
?>

<h1>Корзина покупок</h1>

<nav class="uk-navbar uk-margin hidden-print" id="cart-controls-top">
	<div class="uk-navbar-flip" style="margin-right: 10px;">
		<ul class="uk-subnav uk-subnav-line" style="margin-top: auto;">
			<li>
				<a href="/spareparts"><i class="uk-icon-reply uk-icon-small uk-margin-right uk-icon-hover"></i>Продолжить выбор</a>
			</li>
		</ul>
	</div>
</nav>

<hr class="uk-article-divider">
<div id="product-list">
	<table class="uk-table">
		<thead>
		<tr>
			<td>Товар</td>
			<td></td>
			<td>Цена</td>
			<td>Количество</td>
			<td>Сумма</td>
			<td></td>
		</tr>
		</thead>
	</table>
</div>
<h2 class="uk-text-center uk-margin-large-top">Корзина ждет пополнения...</h2>
