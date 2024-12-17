<?php
include_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'components/com_cobalt/library/php/cobaltcomments.php';

class CobaltCommentsVk extends CobaltComments {
	
	private function _load($type) {
		static $load = null;
	
		if (! $load) {
			$js = 'VK.init({
			    apiId: 6239564,
			    onlyWidgets: true
			  });';
/*			$js = 'VK.init({
			    apiId: '.$type->params->get('comments.appid').',
			    onlyWidgets: true
			  });';*/

			$doc = JFactory::getDocument ();
			$doc->addScript("https://vk.com/js/api/openapi.js?150");
			$doc->addScriptDeclaration ( $js );
			
			$load = TRUE;
		}
	}
	
	public function getNum($type, $item) {
		return 0;
	}
	
	public function getComments($type, $item) {
		$this->_load($type);
		$out = '<h2>Отзывы</h2>';
		$out .= '<div id="vk_comments"></div>
			<script type="text/javascript">
				 VK.Widgets.Comments(\'vk_comments\');
			</script>';
		return $out;
	}
}
