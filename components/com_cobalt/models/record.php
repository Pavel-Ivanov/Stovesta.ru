<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_cobalt' . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'helper.php';

class CobaltModelRecord extends JModelItem
{
	protected $_context = 'com_cobalt.record';
	protected $_item     = [];
	static array $sortable = [];

	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$this->setState('com_cobalt.record.id', JFactory::getApplication()->input->getInt('id'));
	}

	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = !empty($pk) ? $pk : (int)$this->getState('com_cobalt.record.id');

		if (isset($this->_item[$pk])) {
			return $this->_item[$pk];
		}

		try {
			$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__js_res_record');
			$query->where('id = ' . (int)$pk);
			$db->setQuery($query);
			$data = $db->loadObject();

			if(empty($data)) {
				throw new Exception(JText::_('CERR_RECNOTFOUND') . ': ' . $pk, 404);
			}
			$this->_item[$pk] = $data;
		}
		catch(Exception $e) {
			if($e->getCode() === 404) {
				throw new \RuntimeException($e->getMessage(), 404);
			}
			
			$this->_errors[] = $e->getMessage();
			$this->_item[$pk] = false;
		}
		return $this->_item[$pk];
	}

	public function _prepareItem($data, $client = 'full')
	{
		static $fields = [], $fields_model = null, $user = null;
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		if(!$user) {
			$user = JFactory::getUser();
		}
		if(!$fields_model) {
			$fields_model = JModelLegacy::getInstance('Fields', 'CobaltModel');
		}
		$type    = ItemsStore::getType($data->type_id);
		$section = ItemsStore::getSection($data->section_id);

		$data->created = $data->ctime;
		$data->expire  = $data->extime;
		$data->modify  = $data->mtime;
		$data->ctime = JFactory::getDate($data->ctime);
		$data->mtime = JFactory::getDate($data->mtime);
		$data->future = FALSE;
		if($data->ctime->toUnix() > time()) {
			$data->future = TRUE;
		}

		if($section->params->get('general.marknew')) {
			$data->new = empty($data->new);
			if($data->user_id && ($data->user_id == $user->get('id'))) {
				$data->new = FALSE;
			}
		}

		$data->params = new JRegistry($data->params);
		$data->type_name = $type->name;
		
		$data->categories       = json_decode($data->categories, true, 512, JSON_THROW_ON_ERROR);
		$data->categories       = (array) $data->categories;
		$data->categories_links = [];
		$data->category_id      = 0;
		$category_links         = [];
		$cat_ids                = [];
		foreach($data->categories as $cat_id => $title) {
			$data->category_id = $cat_id;
			$cat_ids[]         = $cat_id;
			$category_links[]  = JHtml::link(JRoute::_(Url::records($section, $cat_id)), JText::_($title));
		}
		$data->categories_links = $category_links;

		JArrayHelper::toInteger($cat_ids);
		if($app->input->getInt('cat_id') && in_array($app->input->getInt('cat_id'), $cat_ids, true)) {
			$category_id = $app->input->getInt('cat_id');
		}
		else {
			$category_id = array_shift($cat_ids);
		}

		$data->url  = Url::record($data, $type, $section, $category_id);
		$data->canon  = substr(JUri::base(), 0, -1) . JRoute::_(Url::record($data, $type));
		$data->href = JUri::base() . JRoute::_($data->url);

//		$robots = $type->params->get('submission.robots');
		$data->nofollow = substr_count($type->params->get('submission.robots') ?? '', 'noindex');

		$data->expired = FALSE;
		if($data->extime === '0000-00-00 00:00:00') {
			$data->extime = NULL;
			$data->expire = NULL;
		}
		else {
			$data->extime = JFactory::getDate($data->extime);
			if($data->extime->toUnix() < time() && $data->exalert == 0) {
				$sql = "UPDATE #__js_res_record SET exalert = 1";
				if($type->params->get('properties.item_expire_access')) {
					$sql .= ", access = " . $type->params->get('properties.item_expire_access');
				}
				$sql .= " WHERE id = " . $data->id;

				$db->setQuery($sql);
				$db->execute();

//				CEventsHelper::notify('record', CEventsHelper::_RECORD_EXPIRED, $data->id, $data->section_id, 0, 0, 0, $data, 2);//, $data->user_id);
			}
			if($data->extime->toUnix() < time()) {
				$data->expired = TRUE;
			}
		}

		$fields[$data->id] = $fields_model->getRecordFields($data, 'all');
		
		$sorted = $final = $keyed = [];
		
		foreach($fields[$data->id] as $key => $field) {
			if($field->params->get('params.sortable')) {
				self::$sortable[$field->key] = $field;
			}

			if($client === 'feed' && !$field->params->get('core.show_feed', 0)) {
				continue;
			}
			if($client === 'list' && !$field->params->get('core.show_intro', 0)) {
				continue;
			}
			if($client === 'full' && !$field->params->get('core.show_full', 0)) {
				continue;
			}
			if($client === 'compare' && !$field->params->get('core.show_compare', 0)) {
				continue;
			}

			if(!in_array($field->params->get('core.field_view_access'), $user->getAuthorisedViewLevels())) {
				$result = null;
			}
			else {
				$method = ($client === 'list') ? 'onRenderList' : 'onRenderFull';
				if($field->type === 'image' && $client === 'compare') {
					$method = 'onRenderList';
				}
				$result = $field->$method($data, $type, $section);
			}

			$field->result = $result;

			$keyed[$field->key] = $field;
			$final[$field->id] = $field;
			$sorted[$field->group_title][$field->key] = $field;

			$fg[$field->group_title]['name']  = $field->group_title;
			$fg[$field->group_title]['descr'] = $field->group_descr;
			$fg[$field->group_title]['icon']  = $field->group_icon;
		}

		$data->fields_by_id     = $final;
		$data->fields_by_groups = $sorted;
		$data->fields_by_key    = $keyed;
		$data->field_groups     = @$fg;
		$data->fields           = json_decode($data->fields, true, 512, JSON_THROW_ON_ERROR);
//		$data->rating           = RatingHelp::loadMultiratings($data, $type, $section);
		$data->controls         = $this->_controls($data, $type, $section);
		$data->controls_notitle = $this->_controls($data, $type, $section, TRUE);

		return $data;
	}

	private function _controls($record, $type, $section, $notitle = false) :array
	{
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$view = $app->input->getString('view');
		static $lognums = [];
		static $vernums = [];
		$out = [];

		if(!$user->get('id')) {
			return [];
		}
		$pattern        = '<a class="cobalt-control-item cobalt-control-item-%s" href="%s"><i class="uk-icon-%s"></i> %s</a>';
		$confirm_patern = '<a class="cobalt-control-item cobalt-control-item-%s" href="%s" onclick="if(!confirm(\'%s\')){return false;}">
								<i class="uk-icon-%s"></i> %s</a>';

		if(MECAccess::allowEdit($record, $type, $section)) {
			$out[] = sprintf($pattern, 'edit', Url::edit($record->id . ':' . $record->alias), 'edit', 'Изменить');
		}

		if(MECAccess::allowDelete($record, $type, $section) && $view !== 'record') {
			$out[] = sprintf($confirm_patern, 'delete', Url::task('records.delete', $record->id), 'Вы действительно хотите удалить эту запись?',
				'trash', 'Удалить');
		}
		
		if(MECAccess::allowDelete($record, $type, $section) && $view === 'record') {
			$vw = $app->input->get('view_what');
			$return = base64_encode(JRoute::_(Url::records($record->section_id, $record->category_id, NULL, $vw), FALSE));
			if($app->input->get('api') == 1) {
				$return = FALSE;
			}
			$out[] = sprintf($confirm_patern, 'delete', Url::task('records.delete', $record->id, $return), 'Вы действительно хотите удалить эту запись?',
				'trash', 'Удалить');
		}

		$db = JFactory::getDbo();
		if(MECAccess::allowAuditLog($section)) {
			if(!array_key_exists($record->id, $lognums)) {
				$db->setQuery("SELECT count(*) FROM #__js_res_audit_log WHERE record_id = {$record->id}");
				$lognums[$record->id] = $db->loadResult();
			}

			if($lognums[$record->id]) {
				$url  = 'index.php?option=com_cobalt&view=auditlog&record_id='.$record->id.'&Itemid='.$type->params->get('audit.itemid', $app->input->getInt('Itemid')).'&return='.Url::back();
				$out[] = sprintf($pattern, 'audit', JRoute::_($url), 'history', 'Аудит' . " ({$lognums[$record->id]})");
			}
		}
/*		if(MECAccess::allowRollback($record, $type, $section) || MECAccess::allowCompare($record, $type, $section)) {
			if(!array_key_exists($record->id, $vernums)) {
				$db->setQuery("SELECT * FROM #__js_res_audit_versions WHERE record_id = {$record->id} AND version != {$record->version} ORDER BY version DESC LIMIT 0, 5");
				$vernums[$record->id] = $db->loadObjectList();
			}

			if($vernums[$record->id]) {
				$label   = sprintf($pattern, 'rollback',  'javascript:void(0);', 'arrow-split-090.png', JText::_('CVERCONTRL'), JText::_('CVERCONTRL') . ' - v.' . $record->version);
				$vpatern = "<a>v.%d - by %s on %s</a>";
				foreach($vernums[$record->id] AS $version) {
					$ver = sprintf($vpatern, $version->version, CCommunityHelper::getName($version->user_id, $section, TRUE), JFactory::getDate($version->ctime)->format($type->params->get('audit.audit_date_format', $type->params->get('audit.audit_date_custom'))));

					if(MECAccess::allowRollback($record, $type, $section)) {
						$out[$label][$ver][] = sprintf($pattern, 'version',  Url::task('records.rollback', $record->id . '&version=' . $version->version), 'arrow-merge-180-left.png', JText::_('CROLLBACK'), JText::_('CROLLBACK'));
					}

					if(MECAccess::allowCompare($record, $type, $section)) {
						$url                 = 'index.php?option=com_cobalt&view=diff&record_id=' . $record->id . '&version=' . $version->version . '&return=' . Url::back();
						$out[$label][$ver][] = sprintf($pattern, 'compare',  $url, 'blue-document-view-book.png', JText::_('CCOMPARECUR'), JText::_('CCOMPARECUR'));
					}
				}

				$url           = 'index.php?option=com_cobalt&view=versions&record_id=' . $record->id . '&return=' . Url::back();
				$out[$label][] = sprintf($pattern, 'versions',  $url, 'drawer.png', JText::_('CVERSIONSMANAGE'), JText::_('CVERSIONSMANAGE'));
			}
		}*/

		if($out) {
			return $out;
		}
	}

	public function hit($item, $section_id = null): bool
	{
        $ip = JFactory::getApplication()->input->server->get('REMOTE_ADDR','');
        $userId = JFactory::getUser()->id;
        $db = $this->getDbo();

        $db->setQuery("INSERT INTO #__js_res_hits (record_id, ctime, user_id, ip, section_id) VALUES ($item->id, now(), $userId, '$ip', $section_id)");
        $db->execute();

        $db->setQuery("UPDATE #__js_res_record SET hits = hits + 1 WHERE id = " . $item->id);
        $db->execute();
        return true;
	}
}
