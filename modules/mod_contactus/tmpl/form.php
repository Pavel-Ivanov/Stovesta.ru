<?php 
if (!isset($dependencys)) {
    $dependencys = [];
}
?>
<div id="contactus-form<?= $module->id ?? '' ?>" class="contactus-form contactus-form<?= $module->id ?? '' ?> <?php if (isset($fields->margin)){echo "contactus-".$fields->margin;} ?>">
	<form  class="reg_form"  action="<?= JFactory::getURI() ?>" id="contactusForm<?= $module->id ?? '' ?>"
           onsubmit="contactus_validate(this); joomly_analytics(<?= $module->id ?>);" method="post" enctype="multipart/form-data">
		<div>
			<?php if (isset($fields->field)) {
					$i = 0;
					foreach ($fields->field as $k=>$f) {
                        if ($f->dependency !== "") {
                            $temp_d = explode(":",$f->dependency);
                            $dependencys[$temp_d[0].$module->id][] = array("value"=>$temp_d[1], "child" => $f->name.$module->id);
                        }
						switch ($f->type){
							case "Input":?>
								<div class="joomly-contactus-div">
									<input type="text" placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>"
                                           class="contactus-fields <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"
                                           name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";} ?>
                                           value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>"
                                    />
								</div>
						<?php break; 
							case "Email":?>
								<div class="joomly-contactus-div">
									<input type="email" placeholder="<?= $f->title;if ($f->required == 1){echo '*';} ?>"
                                           class="contactus-fields <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"
                                           name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";} ?>
                                           value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>"
                                    />
								</div>
						<?php break; 
							case "Phone":?>
								<div class="joomly-contactus-div">
									<input type="tel" pattern="(\+?\d[- .\(\)]*){5,15}" placeholder="<?= $f->title;if ($f->required == 1){echo '*';} ?>"
                                           class="contactus-fields <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"
                                           name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";} ?>
                                           value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>"
                                    />
								</div>
						<?php break; 
							case "Textarea":?>
								<div class="joomly-contactus-div">
									<textarea  placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>"
                                               class="contactus-textarea <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"
                                               name="<?= $f->name ?>" cols="120" rows="6" <?php if ($f->required == 1){echo "required";} ?>><?php if (isset($data[$f->name])){echo $data[$f->name];} ?>
                                    </textarea>
								</div>
						<?php break; 
							case "Date":?>
								<div class="joomly-contactus-div">
									<input type="text" placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>"
                                           class="contactus-fields constructor <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>" onfocus="(this.type='date')"
                                           name="<?= $f->name;?>" <?php if ($f->required == 1){echo "required";} ?>
                                           value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>"
                                    />
								</div>	
						<?php break; 
							case "Time":?>
								<div class="joomly-contactus-div">
									<input type="text" placeholder="<?= $f->title; if ($f->required == 1){echo '*';} ?>"
                                           class="contactus-fields constructor <?php echo $f->name.$module->id;?>" data-id="<?= $k.$module->id ?>" onfocus="(this.type='time')"
                                           name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";} ?>
                                           value="<?php if (isset($data[$f->name])){echo $data[$f->name];} ?>"
                                    />
								</div>	
						<?php break; 
							case "Checkbox":?>
								<div class="joomly-contactus-div checkbox-container">
									<label class="contactus-checkbox-label" ><span class="contactus-sp"><?= $f->title ?></span>
                                        <input type="checkbox" class="contactus-fields <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"
                                               value="<?= JText::_('MOD_CONTACTUS_CHECKBOX_YES') ?>"
                                               name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";} ?>
                                        />
                                    </label>
								</div>		
						<?php break; 
							case "Select":?>
								<div class="joomly-contactus-div select-container">
									<select class="contactus-select <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"
                                            name="<?= $f->name ?>" <?php if ($f->required == 1){echo "required";} ?>>
                                        <option <?php if ($f->required == 1){echo 'selected="selected" disabled="disabled" value=""';} ?>>
                                            <?= $f->title; if ($f->required == 1){echo '*';} ?>
                                        </option>
                                        <?php foreach ($f->options as $option) {?>
                                            <option><?= $option ?></option>
                                        <?php }?>
									</select>
								</div>
						<?php break; 
							case "Text":?>
								<div class="joomly-contactus-div">
									<p class="joomly-p <?= $f->name.$module->id ?>" data-id="<?= $k.$module->id ?>"><?= $f->title ?></p>
								</div>						
						<?php break;		
						}
						++$i;
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
            <div class="joomly-contactus-div">
                <div class="g-recaptcha <?php if (isset($fields->margin)){echo "contactus-".$fields->margin;} ?>"
                     data-sitekey="<?php if (isset($fields->captcha_sitekey)){echo $fields->captcha_sitekey;}?>"
                     data-size="<?php if (isset($fields->captcha_size)){echo $fields->captcha_size;}?>">
                </div>
            </div>
        </div>
		<div>
            <button type="submit" value="save" class="<?php if ((isset($fields->captcha_size)) && ($fields->captcha_size === 'invisible')){echo 'g-recaptcha';} ?> contactus-button contactus-submit contactus-<?php if (isset($fields->margin)){echo $fields->margin;} ?>"
                    style="background-color: <?php echo (isset($fields->color) ? $fields->color : "#21ad33");?>;"
                    id="button-contactus-lightbox<?php if (isset($module->id)){echo $module->id;} ?>"
                <?php if (($fields->captcha !==null ? $fields->captcha : 1)  == 1){echo "data-callback='submitForm' data-sitekey='" . $fields->captcha_sitekey . "'";} ?>
            >
                <?php if (!empty($fields->button_send)) { echo $fields->button_send;} else {echo  JText::_('MOD_CONTACTUS_SEND');} ?>
            </button>

<!--            <button type="submit" value="save" class="contactus-button contactus-submit contactus-<?php /*if (isset($fields->margin)){echo $fields->margin;} */?>"
                    style="background-color: <?php /*echo (isset($fields->color) ? $fields->color : "#21ad33");*/?>;" id="button-contactus-lightbox<?php /*= $module->id ?? '' */?>">
                <?php /*if (!empty($fields->button_send)) { echo $fields->button_send;} else {echo  JText::_('MOD_CONTACTUS_SEND');} */?>
            </button>
-->		</div>
			<input type="hidden" name="option" value="com_contactus" />
			<input type="hidden" name="layout" value="form" />
			<input type="hidden" name="module_id" value="<?= $module->id ?>" />
			<input type="hidden" name="module_title" value="<?= $module->title ?>" />
			<input type="hidden" name="module_hash" value="<?= JUserHelper::getCryptedPassword(JFactory::getURI()->toString()) ?>" />
			<input type="hidden" name="page" value="<?= urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" />
			<input type="hidden" name="ip" value="<?= $_SERVER['REMOTE_ADDR'] ?>" />
			<input type="hidden" name="task" value="add.save" />
			<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<div class="contactus-alert" id="contactus-sending-alert<?= $module->id ?? '' ?>">
	<div class="contactus-lightbox-caption" style="background-color:<?= $alert_message_color;?>;">
		<div class="contactus-lightbox-cap">
            <h4 class="contactus-lightbox-text-center">
                <?php if (isset($alert_headline_text)){echo $alert_headline_text;} ?>
            </h4>
        </div>
        <div class="contactus-lightbox-closer">
            <i id="contactus-lightbox-sending-alert-close<?= $module->id ?? '' ?>" class="fa fa-close fa-1x"></i>
        </div>
	</div>
	<div class="contactus-alert-body">
		<p class="contactus-lightbox-text-center"><?php if (isset($alert_message_text)){echo $alert_message_text;} ?></p>
	</div>
</div>
<script>
var dependencys = <?= json_encode($dependencys) ?>;
set_dependencys(dependencys);
let contactus_module_id = <?= $module->id ?? '' ?>,
files_added = "<?= JText::_('MOD_CONTACTUS_FILES_ADDED') ?>";
type_field = "<?= JText::_('MOD_CONTACTUS_TYPE_FIELD') ?>";
var contactus_params = contactus_params || [];
contactus_params[contactus_module_id] = <?= json_encode($contactus_params) ?>;
contactus_form();
</script>
