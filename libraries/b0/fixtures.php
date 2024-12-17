<?php
function b0debug ($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

function b0dd ($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	die();
}