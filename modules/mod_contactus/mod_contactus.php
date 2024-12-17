<?php
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$app  = JFactory::getApplication();
//Get UserState data
$data = $app->getUserState('contactus.add.form.data', []);
$app->setUserState('contactus.add.form.data', []);
//Get the module options and layout
$fields = ModContactusHelper::getFields($module->id);
$contactus_params = new StdClass();
$contactus_params->form_max_width = !empty($fields->form_max_width) ? $fields->form_max_width : 400; 
$layout = $fields->lightbox ?? "form";
//Get and set variables for the sending message 
$alert_headline_text = (!empty($fields->alert_title_name)) ?  $fields->alert_title_name : JText::_('MOD_CONTACTUS_TITLE_NAME_MODULE');
$alert_message_text = (!empty($fields->alert_message)) ? $fields->alert_message : JText::_('MOD_CONTACTUS_ALERT');
$alert_message_color = ($fields->color ?? "#21ad33");
//Get classes for the call button
$class = isset($fields->sticky_align) ? ("align-".$fields->sticky_align) : "";
$button_wall = ($fields->sticky_align === 'bottom') ? "left" : "top";
$button_align = isset($fields->sticky_align_val) ? $button_wall.":".$fields->sticky_align_val.";" : "";
//Add stylesheet
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'modules/mod_contactus/css/contactus_'.$layout.'.css');
$doc->addScript("modules/mod_contactus/js/contactus_common.js");
$doc->addScript( 'modules/mod_contactus/js/contactus_'.$layout.'.js');
if (($fields->captcha !==null ? $fields->captcha : 1)  == 1){
    $doc->addScript("https://www.google.com/recaptcha/api.js", 'text/javascript', true, true);
}

if ((!empty($fields->yandex_metrika_id)) || (!empty($fields->google_analytics_category))) {
	$contactus_params->yandex_metrika_id = $fields->yandex_metrika_id; 
	$contactus_params->yandex_metrika_goal = $fields->yandex_metrika_goal;
	$contactus_params->google_analytics_category = $fields->google_analytics_category; 
	$contactus_params->google_analytics_action = $fields->google_analytics_action;
	$contactus_params->google_analytics_label = $fields->google_analytics_label;
	$contactus_params->google_analytics_value =  $fields->google_analytics_value;
}
if ($fields->sms_flag) {
	$contactus_params->sms_flag = 1;
	$contactus_params->sms_self_number = $fields->sms_self_number;
	$contactus_params->sms_text = $fields->sms_text;
}
require JModuleHelper::getLayoutPath('mod_contactus', $layout);
