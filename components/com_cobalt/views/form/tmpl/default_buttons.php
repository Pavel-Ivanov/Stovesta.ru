<?php
defined('_JEXEC') or die();
?>
<div class="uk-panel uk-panel-box uk-panel-box-secondary">
    <?php if(!$this->isCheckedOut()):?>
        <button type="button" class="uk-button uk-button-link" title="Сохранить без выхода" onclick="Joomla.submitbutton('form.apply');">
            <i class="uk-icon-check uk-text-success"></i>
            Применить
        </button>
        <button type="button" class="uk-button uk-button-link" title="Сохранить и закрыть" onclick="Joomla.submitbutton('form.save');">
            <i class="uk-icon-save uk-text-success"></i>
            Сохранить
        </button>
        <button type="button" class="uk-button uk-button-link" title="Сохранить и добавить новую запись" onclick="Joomla.submitbutton('form.save2new');">
            <i class="uk-icon-plus uk-text-success"></i>
            Новая
        </button>
        <button type="button" class="uk-button uk-button-link" title="Сохранить и скопировать" onclick="Joomla.submitbutton('form.save2copy');">
            <i class="uk-icon-copy uk-text-success"></i>
            Копировать
        </button>
    <?php endif; ?>
    <button type="button" class="uk-button uk-button-link" title="Закрыть без сохранения" onclick="Joomla.submitbutton('form.cancel');">
        <i class="uk-icon-close"></i>
        Выход
    </button>
</div>
