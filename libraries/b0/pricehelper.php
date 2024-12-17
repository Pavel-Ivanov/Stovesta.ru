<?php
/**
 * @return string
 */
function render_price_by_order() {
//	echo '<div class="ls-price-first">';
	echo '<p class="ls-price-first">Ожидается поступление</p>';
	echo '<p class="ls-price-second">Цена уточняется при заказе</p>';
//	echo '</div>';
	echo JMicrodata::htmlMeta('Уточняется при заказе', 'price');
	echo JMicrodata::htmlMeta('RUB', 'priceCurrency');
	echo JMicrodata::htmlMeta('Ожидается поступление', 'availability');
}

/**
 * @param array $prices
 *
 * @return string
 */
function render_price_featured($prices) {
	//TODO вставить разметку для спецпредложения
	echo '<div class="ls-price-special uk-text-danger uk-margin-top">';
	echo 'Специальная цена: ' . render_price($prices['special']);
	echo '</div>';
	echo '<div class="ls-price-second uk-margin-top">Обычная цена: <del>'.
		render_price($prices['general']).
		'</del></div>';
	echo '<div class="uk-margin-top">'.
		'Вы экономите ' . render_economy($prices['general'], $prices['special']).
		'</div>';
	echo JMicrodata::htmlMeta($prices['special'], 'price');
	echo JMicrodata::htmlMeta('RUB', 'priceCurrency');
	echo JMicrodata::htmlMeta('В наличии', 'availability');
}

/**
 * @param array $prices
 *
 * @return string
 */
function render_price_general($prices) {
	echo '<div class="ls-price-first uk-text-center-small uk-margin-top">';
	echo 'Цена: ' . render_price($prices['general']);
	echo '</div>';
	echo JMicrodata::htmlMeta($prices['general'], 'price');
	echo JMicrodata::htmlMeta('RUB', 'priceCurrency');
	echo JMicrodata::htmlMeta('В наличии', 'availability');
}

/**
 * @param integer $price
 *
 * @return string
 */
function render_price($price) {
//	return number_format(round($price, -1), 0, '.', ' ') . ' RUB';
	return number_format($price, 0, '.', ' ') . ' руб.';
}

/**
 * @param integer $price1
 * @param integer $price2
 *
 * @return string
 */
function render_economy ($price1, $price2) {
	$delta = $price1 - $price2;
//	$delta = round($price1, -1) - round($price2, -1);
	$percent = number_format(($price1 - $price2 / $price1) * 100, 0);
//	$percent = number_format(($delta / $price1) * 100, 0);
	//return number_format($delta, 0, '.', ' ') . ' RUB ( ' . $percent . ' % )';
	return number_format($delta, 0, '.', ' ') . ' руб.';
}

/**
 * Возвращает укороченный заголовок
 *
 * @param $title
 * @param $cut_len
 *
 * @return string
 *
 * @since version
 */
function get_clipped_title($title, $cut_len) {
	return ((mb_strlen($title) > $cut_len) ? (mb_substr($title, 0, $cut_len-3) . '...') : $title);
}

// Вывод заголовка закладки
/**
 * @param $tab_title
 * @param $is_active
 */
function tab_title($tab_title, $is_active) {
	echo
		'<li' . (($is_active == 1) ? ' class="uk-active"' : '') . '>'
		. '<a href="">' . $tab_title . '</a>'
		. '</li>'
	;
}

/**
 * Вывод контента закладки
 *
 * @param mixed $field
 */
function tab_content($field) {
	echo '<li class="">' . $field . '</li>';
}

function render_field($field, $user, $meta='') {
	if (isset($field) && in_array($field->params->get('core.field_view_access'), $user->getAuthorisedViewLevels())) {
		echo '<p>';
		echo '<strong>'.$field->label.': </strong>'.$field->result;
		echo '</p>';
		if ($meta) {
			echo JMicrodata::htmlMeta(strip_tags($field->result), $meta);
		}
	}
}
