<?php
defined('_JEXEC') or die();

$class[] = $this->params->get('core.field_class', 'inputbox');
$required = NULL;

if ($this->required) {
	$class[] = 'required';
	$required = ' required="true" ';
}

$class = ' class="' . implode(' ', $class) . '"';
$size = $this->params->get('params.size') ? ' style="width:' . $this->params->get('params.size') . '"' : '';
$maxLength = $this->params->get('params.maxlength') ? ' maxlength="' . (int)$this->params->get('params.maxlength') . '"' : '';
$readonly = ((string)$this->params->get('readonly') == 'true') ? ' readonly="readonly"' : '';
$disabled = ((string)$this->params->get('disabled') == 'true') ? ' disabled="disabled"' : '';
$onchange = $this->params->get('onchange') ? ' onchange="' . (string)$this->params->get('onchange') . '"' : '';

$mask = $this->params->get('params.mask', 0);
?>

<?= $this->params->get('params.prepend') ?>

<input type="text" id="field_<?= $this->id ?>" name="jform[fields][<?= $this->id ?>]"
       placeholder="<?= $this->params->get('params.show_mask', 1) ? $this->params->get('params.mask.mask') : NULL ?>"
       value="<?= htmlspecialchars($this->value ?? '', ENT_COMPAT, 'UTF-8') ?>"
    	<?= $class . $size . $disabled . $readonly . $onchange . $maxLength . $required ?>
>
<?= $this->params->get('params.append') ?>
				
<?php if ($mask->mask_type) :?>
    <script>
        initMask(<?= $this->id ?>, "<?= $mask->mask ?>", "<?= $this->mask_type ?>");
    </script>
<?php endif; ?>