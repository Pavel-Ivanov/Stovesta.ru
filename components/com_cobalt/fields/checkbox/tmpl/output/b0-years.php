<?php
defined('_JEXEC') or die();
if (count($this->values) == 1) {
	echo $this->values[0];
}
else {
	echo min($this->values).'-'.max($this->values);
}
