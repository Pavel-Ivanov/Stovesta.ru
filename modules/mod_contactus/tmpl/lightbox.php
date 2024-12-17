<?php 
if (!isset($dependencys)) {
    $dependencys = [];
}
?>
<div id="contactus-lightbox<?= $module->id ?? '' ?>" class="contactus-lightbox contactus-lightbox<?= $module->id ?? '' ?>">
	<div class="contactus-lightbox-caption" style="background-color:<?php echo ($fields->color ?? "#21ad33");?>;">
		<div class="contactus-lightbox-cap">
            <p class="uk-h4 text-center">
                <?php if (!empty($fields->title_name)) {
                    echo $fields->title_name;
                }
                else {
                    echo JText::_('MOD_CONTACTUS_TITLE_NAME_MODULE');
                } ?>
            </p>
        </div>
        <div class="contactus-lightbox-closer">
            <i id="contactus-lightbox-close<?= $module->id ?? '' ?>" class="fa fa-close fa-1x"></i>
        </div>
	</div>
	<div class="contactus-lightbox-body">
<!--		<form  action="<?php /*= JFactory::getURI() */?>" method="post" class="reg_form" id="contactusForm<?php /*= $module->id ?? '' */?>"
               onsubmit="contactus_validate(this);yaCounter46027323.reachGoal('vacans'); return true;" enctype="multipart/form-data">
-->		<form  action="<?= JFactory::getURI() ?>" method="post" class="reg_form" id="contactusForm<?= $module->id ?? '' ?>"
               onsubmit="contactus_validate(this);joomly_analytics(<?= $module->id ?? '' ?>); return true;" enctype="multipart/form-data">
            <?php if (isset($fields->field)) {
                    $i = 0;
                    foreach ($fields->field as $k=>$f) {
                        switch ($f->type) {
                            case "Input":
                                if ($f->dependency != "") {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <input type="text" placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>" class="contactus-fields <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id;?>" name="<?= $f->name;?>" <?php if ($f->required == 1){echo "required";} ?> value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>" />
                                </div>
                        <?php break;
                            case "Email":
                                if ($f->dependency != "") {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <input type="email" placeholder="<?= $f->title;if ($f->required == 1){echo '*';} ?>" class="contactus-fields <?= $f->name.$module->id;?>" data-id="<?= $k.$module->id;?>" name="<?php echo $f->name;?>" <?php if ($f->required == 1){echo "required";} ?> value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>" />
                                </div>
                        <?php break;
                        case "Phone":
                                if ($f->dependency != "") {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <input type="tel" pattern="(\+?\d[- .\(\)]*){5,15}" placeholder="<?= $f->title;if ($f->required == 1){echo '*';} ?>" class="contactus-fields <?= $f->name.$module->id;?>" data-id="<?= $k.$module->id;?>" name="<?= $f->name;?>" <?php if ($f->required == 1){echo "required";} ?> value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>" />
                                </div>
                        <?php break;
                            case "Textarea":
                                if ($f->dependency != "")
                                {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <textarea  placeholder="<?= $f->title;if ($f->required == 1){echo '*';} ?>" class="contactus-textarea <?= $f->name.$module->id;?>" data-id="<?= $k.$module->id;?>" name="<?= $f->name;?>" cols="120" rows="6" <?php if ($f->required == 1){echo "required";} ?>>
                                        <?php if (isset($data[$f->name])){echo $data[$f->name];}?>
                                    </textarea>
                                </div>
                        <?php break;
                            case "Date":
                                if ($f->dependency != "")
                                {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <input type="text" placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>" class="contactus-fields constructor <?= $f->name.$module->id;?>" data-id="<?= $k.$module->id;?>" onfocus="(this.type='date')" name="<?= $f->name;?>" <?php if ($f->required == 1){echo "required";} ?> value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>" />
                                </div>
                        <?php break;
                        case "Time":
                                if ($f->dependency != "")
                                {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <input type="text" placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>" class="contactus-fields constructor <?= $f->name.$module->id;?>" data-id="<?= $k.$module->id;?>" onfocus="(this.type='time')" name="<?= $f->name;?>" <?php if ($f->required == 1){echo "required";}; ?> value="<?php if (isset($data[$f->name])){echo $data[$f->name];};?>" />
                                </div>
                        <?php break;
                            case "Checkbox":
                                if ($f->dependency != "")
                                {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div checkbox-container">
                                    <label class="contactus-checkbox-label" ><span class="contactus-sp"><?php echo $f->title;?></span><input type="checkbox" class="contactus-fields <?= $f->name.$module->id;?>" data-id="<?= $k.$module->id;?>" value="<?= JText::_('MOD_CONTACTUS_CHECKBOX_YES');?> " name="<?php echo $f->name;?>" <?php if ($f->required == 1){echo "required";}; ?>/></label>
                                </div>
                        <?php break;
                            case "Select":
                                if ($f->dependency != "")
                                {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div select-container">
                                    <select class="contactus-select <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id;?>" name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";}; ?>>
                                    <option <?php if ($f->required == 1){echo 'selected="selected" disabled="disabled" value=""';} ?>>
                                        <?= $f->title; if ($f->required == 1){echo '*';}?>
                                    </option>
                                    <?php foreach ($f->options as $option):?>
                                        <option><?= $option ?></option>
                                    <?php endforeach;?>
                                    </select>
                                </div>
                        <?php break;
                            case "Text":
                                if ($f->dependency != "")
                                {
                                    $temp_d = explode(":",$f->dependency);
                                    $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                                } ?>
                                <div class="joomly-contactus-div">
                                    <p class="joomly-p <?php echo $f->name.$module->id;?>" data-id="<?php echo $k.$module->id;?>"><?php echo $f->title;?></p>
                                </div>
                        <?php break;
                        }
                        $i+=1;
                     }
                }?>
            <div class="joomly-contactus-div">
                <small>
                    Нажимая кнопку «<?= $fields->button_send ?>», я даю свое согласие на обработку моих персональных данных,
                    в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных»,
                    на условиях и для целей, определенных в
                    <a href="<?= $fields->personal_link ?>" target="_blank">
                        Согласии на обработку персональных данных
                    </a>
                </small>
            </div>
            <?php if ((($fields->captcha !==null ? $fields->captcha : 1)  == 1) && ($fields->captcha_size !== 'invisible')){?>
                <div class="joomly-contactus-div">
                    <div class="g-recaptcha <?php if (isset($fields->margin)){echo "contactus-".$fields->margin;}; ?>" data-sitekey="<?php if (isset($fields->captcha_sitekey)){echo $fields->captcha_sitekey;}?>" data-size="<?php if (isset($fields->captcha_size)){echo $fields->captcha_size;}?>">
                    </div>
                </div>
            <?php }?>
            <button type="submit" value="save" class="<?php if ((($fields->captcha ?? 1)  == 1)  && ($fields->captcha_size === 'invisible')){echo 'g-recaptcha';}?> contactus-button contactus-submit" style="background-color: <?php echo (isset($fields->color) ? $fields->color : "#21ad33");?>;" id="button-contactus-lightbox<?php if (isset($module->id)){echo $module->id;};?>" <?php if (($fields->captcha !==null ? $fields->captcha : 1)  == 1){echo "data-callback='submitForm' data-sitekey='" . $fields->captcha_sitekey . "'";};?>>
                <?php if (!empty($fields->button_send)) {
                    echo $fields->button_send;
                }
                else {
                    echo  JText::_('MOD_CONTACTUS_SEND');
                } ?>
            </button>

<!--            <button type="submit" value="save" class="contactus-button contactus-submit" style="background-color: <?php /*= $fields->color ?? "#21ad33" */?>;"
                    id="button-contactus-lightbox<?php /*= $module->id ?? '' */?>" >
                <?php /*= $fields->button_send ?? JText::_('MOD_CONTACTUS_SEND') */?>
            </button>
-->			<input type="hidden" name="option" value="com_contactus" />
			<input type="hidden" name="layout" value="lightbox" />
			<input type="hidden" name="module_id" value="<?= $module->id ?>" />
			<input type="hidden" name="module_title" value="<?= $module->title ?>" />
			<input type="hidden" name="module_hash" value="<?= JUserHelper::getCryptedPassword(JFactory::getURI()->toString()) ?>" />
			<input type="hidden" name="page" value="<?= urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" />
			<input type="hidden" name="ip" value="<?= $_SERVER['REMOTE_ADDR'] ?>" />
			<input type="hidden" name="task" value="add.save" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>	
<?php if (($fields->lightbox_element !==null ? $fields->lightbox_element : 1) > 0):?>
    <div>
        <button class="<?php if ($fields->lightbox_element == 2){echo "sliding ".$class;} ?> contactus-<?= $fields->margin ?? '' ?> contactus<?= $module->id  ?> contactus-button"
                style="background-color: <?php echo (isset($fields->color) ? $fields->color : "#21ad33");?>; <?php echo $button_align ;?>" id="button-contactus-lightbox-form<?= $module->id ?>">
            <?php if (!empty($fields->lightbox_button_caption)) {
                echo $fields->lightbox_button_caption;
            }
            else {
                echo  JText::_('MOD_CONTACTUS_WRIGHT_TO_US');
            } ?>
        </button>
    </div>
<?php endif; ?>
<div class="contactus-alert" id="contactus-sending-alert<?= $module->id ?? '' ?>">
	<div class="contactus-lightbox-caption" style="background-color:<?= $alert_message_color;?>;">
		<div class="contactus-lightbox-cap">
            <p class="uk-h4 contactus-lightbox-text-center">
                <?php if (isset($alert_headline_text)){echo $alert_headline_text;} ?>
            </p>
        </div>
        <div class="contactus-lightbox-closer">
            <i id="contactus-lightbox-sending-alert-close<?php if (isset($module->id)){echo $module->id;} ?>" class="fa fa-close fa-1x"></i>
        </div>
	</div>
	<div class="contactus-alert-body">
		<div class="contactus-lightbox-text-center">
            <?= $alert_message_text ?? '' ?>
        </div>
	</div>
</div>

<script>
var dependencys = <?= json_encode($dependencys, JSON_THROW_ON_ERROR);?>;
set_dependencys(dependencys);
var contactus_module_id = <?php if (isset($module->id)){echo $module->id;} ?>,
//files_added = "<?php //= JText::_('MOD_CONTACTUS_FILES_ADDED') ?>//";
type_field = "<?= JText::_('MOD_CONTACTUS_TYPE_FIELD') ?>";
captcha_error = "<?= JText::_('MOD_CONTACTUS_CAPTCHA_ERROR') ?>//";
//filesize_error = "<?php //= JText::_('MOD_CONTACTUS_FILESIZE_ERROR') ?>//";
var uploads_counter = uploads_counter || [];
uploads_counter[contactus_module_id] = 0;
var contactus_params = contactus_params || [];
contactus_params[contactus_module_id] = <?= json_encode($contactus_params, JSON_THROW_ON_ERROR) ?>;
var popup = document.getElementById("contactus-lightbox" + contactus_module_id);
document.body.appendChild(popup);
contactus_lightbox();
</script>
