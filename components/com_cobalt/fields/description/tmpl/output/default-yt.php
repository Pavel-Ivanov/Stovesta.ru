<?php
defined('_JEXEC') or die;

$value = $this->value;
?>
<?php if(count($value) > 0): ?>
	<dl class="uk-description-list-horizontal">
        <?php foreach($value as $i => $val):?>
            <dt><?= $val['label'] ?></dt>
            <dd><?= $val['url'] ?></dd>
        <?php endforeach; ?>
	</dl>
<?php endif; ?>
