<?php
defined('_JEXEC') or die();
if(!$this->values) return;
$default = $this->default;
//b0debug($this->default);
?>
<ul class="uk-grid uk-text-center" data-uk-grid-margin>
	<?php foreach($this->values as $key => $value) :?>
		<?php if (!$value->field_value) {
			continue;
		}
		$label = $this->_getVal($value->field_value);
		?>
        <li class="uk-width-medium-1-5">
            <label>
                <input type="checkbox" name="filters[<?= $this->key ?>][value][]" value="<?= htmlspecialchars($value->field_value) ?>"
					<?= (in_array($value->field_value, $default, true) ? ' checked="checked"' : NULL) ?>>
				<?= $label ?>
                <span class="badge"><?= ($this->params->get('params.filter_show_number', 1) ? $value->num : NULL) ?></span>
            </label>
        </li>
	<?php endforeach; ?>
</ul>
