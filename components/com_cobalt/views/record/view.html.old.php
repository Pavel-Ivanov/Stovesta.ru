<?php
defined('_JEXEC') or die();
JImport('b0.fixtures');
JImport('joomla.application.component.view');
JHtml::addIncludePath(JPATH_COMPONENT . '/library/php');

class CobaltViewRecord extends JViewLegacy
{
	public function display($tpl = NULL)
	{
		JHtml::_('dropdown.init');

		$app  = JFactory::getApplication();
//		$doc  = JFactory::getDocument();
		$user = JFactory::getUser();

//		$tmpl_params = [];
		$category    = NULL;

		$item    = $this->get('Item');
		/** @var CobaltModelRecord $model */
		$model = $this->getModel();
		$section = ItemsStore::getSection($item->section_id);
//		$db      = JFactory::getDbo();

		$this->menu_params = $app->getMenu()->getParams($app->input->get('Itemid'));

		$app->input->set('section_id', $item->section_id);

		if(!$this->_checkItemAccess($item, $section)) {
			return;
		}

		$type = ItemsStore::getType($item->type_id);

		if($type->published == 0) {
			JError::raise(E_ERROR, 404, JText::_('CMSG_TYPEUNPUB'));
			return;
		}

		if($section->published == 0) {
			JError::raise(E_ERROR, 404, JText::_('CERR_SECTIONUNPUB'));
			return;
		}

		if(!$section->params->get('general.status', 1)) {
			JError::raise(E_ERROR, 404, JText::_($section->params->get('general.status_msg')));
			$error = FALSE;
		}

		if(!in_array($section->access, $user->getAuthorisedViewLevels())) {
			JError::raise(E_ERROR, 404, JText::_($section->params->get('general.status_msg')));
			$error = FALSE;
		}

		if(!$this->_checkCategoryAccess($item, $section)) {
			JError::raise(E_ERROR, 404, JText::_($section->params->get('general.status_msg')));
			$error = FALSE;
		}

//		$dir = JPATH_ROOT . '/components/com_cobalt/views/record/tmpl';

		if($this->menu_params->get('tmpl_article')) {
			$this->tmpl_params['record'] = CTmpl::prepareTemplate('default_record_', 'tmpl_article', $this->menu_params);
		}
		else {
			$this->tmpl_params['record'] = CTmpl::prepareTemplate('default_record_', 'properties.tmpl_article', $type->params);
		}

		$item = $model->_prepareItem($item, 'full');
		$this->fields_keys_by_id = JModelLegacy::getInstance('Records', 'CobaltModel')->getKeys($section);

		$cat_id = $app->input->getInt('cat_id', @$item->category_id);
		if($cat_id) {
			$category = ItemsStore::getCategory($cat_id);
		}
		else {
			require_once JPATH_ROOT . '/components/com_cobalt/models/category.php';
			$cat_model = new CobaltModelCategory();
			$category  = $cat_model->getEmpty();
		}

		$this->user     = $user;
		$this->item     = $item;
		$this->type     = $type;
		$this->section  = $section;
		$this->category = $category;
		$this->print    = $app->input->getBool('print', FALSE);

		$model->hit($item, $section->id);
		ATlog::log((int)$this->item->id, ATlog::REC_VIEW);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	private function _checkCategoryAccess($item, $section)
	{
		if(!$item->categories) {
			return TRUE;
		}
		
		$user = JFactory::getUser();
		if($item->user_id == $user->get('id') && $user->get('id')) {
			return TRUE;
		}

		$categories = json_decode($item->categories, true, 512, JSON_THROW_ON_ERROR);
		foreach($categories as $id => $title) {
			if(empty($id)) {
				continue;
			}

			$cat = ItemsStore::getCategory($id);
			if(!in_array($cat->access, $user->getAuthorisedViewLevels())) {
				return FALSE;
			}

			if(JFactory::getApplication()->input->get('cat_id') == $id && $cat->published == 0) {
				JError::raise(E_NOTICE, 100, 'CNOTICE_THIS_RECORD_INVISIBLE_IN_UNPUBLISHED_CATEGORY');
				return FALSE;
			}
		}
		return TRUE;
	}

	private function _checkItemAccess($item, $section)
	{
		$user  = JFactory::getUser();
		$error = TRUE;
		$app   = JFactory::getApplication();
		$db    = JFactory::getDbo();

		if(
			!in_array($item->access, $user->getAuthorisedViewLevels()) &&
			!MECAccess::allowRestricted($user, $section) &&
			!($user->get('id') == $item->user_id && $item->user_id)
		)
		{

			if($item->parent_id && $item->parent == 'com_cobalt') {
				$parent = ItemsStore::getRecord($item->parent_id);
				if(!($user->get('id') && $user->get('id') == $parent->user_id)) {
					JError::raise(E_WARNING, 403, JText::_('CWARNING_NO_ACCESS_ARTICLE'));
					$error = FALSE;
				}
			}
			elseif($app->input->get('access')) {
				$ids = explode(',', CobaltFilter::base64($app->input->get('access')));

				$sql = "SELECT params from `#__js_res_fields` WHERE id = " . $ids[0];
				$db->setQuery($sql);
				$params = new JRegistry($db->loadResult());

				if(!$params->get('params.show_relate')) {
					JError::raise(E_WARNING, 403, JText::_('CWARNING_NO_ACCESS_ARTICLE'));
					$error = FALSE;
				}
				else {
					if(empty($ids[1])) {
						$parent_user = $user->get('id');
					}
					else {
						$sql = "SELECT user_id from `#__js_res_record` WHERE id = " . $ids[1];
						$db->setQuery($sql);
						$parent_user = $db->loadResult();
					}

					if(!($parent_user && $parent_user == $user->get('id'))) {
						JError::raise(E_WARNING, 403, JText::_('CWARNING_NO_ACCESS_ARTICLE'));
						$error = FALSE;
					}
				}
			}
			else {
				JError::raise(E_WARNING, 403, JText::_('CWARNING_NO_ACCESS_ARTICLE'));
				$error = FALSE;
			}
		}
		//TODO изменения 404 по неопубликованнй статье
		if($item->published == 0 || $item->hidden == 1) {
			JError::raise(E_ERROR, 404, JText::_('CWARNING_RECORD_UNPUBLISHED'));
			$error = FALSE;
		}

		$ctreated = JFactory::getDate($item->ctime)->toUnix();
		$expire   = JFactory::getDate($item->extime)->toUnix();
		$now      = JFactory::getDate()->toUnix();

		if(($now > $expire && ($item->extime != '0000-00-00 00:00:00')) && !in_array($section->params->get('general.show_past_records'), $user->getAuthorisedViewLevels()) && !MECAccess::allowRestricted($user, $section)) {
			echo JText::_('CWARNING_RECORD_EXPIRED');
			$error = FALSE;
		}

		if(($now < $ctreated) && !in_array($section->params->get('general.show_future_records'), $user->getAuthorisedViewLevels()) && !MECAccess::allowRestricted($user, $section)) {
			echo JText::_('CWARNING_RECORD_NOT_YET_PUBLISHED');
			$error = FALSE;
		}

		return $error;
	}

	protected function _prepareDocument()
	{
		$app             = JFactory::getApplication();
		$menus           = $app->getMenu();
		$pathway         = $app->getPathway();
		$title           = NULL;
		$meta            = [];
		$this->appParams = $app->getParams();
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if($this->item->title) {
			$title = $this->item->title;
		}

		if(!$app->input->getInt('Itemid', FALSE)) {
			$path[] = [
				'title' => $this->section->name,
				'link'  => JRoute::_(Url::records($this->section))
			];
		}
		if($this->category->id) {
			if($this->category->parent_id == 1) {
				$path[] = [
					'title' => JText::_($this->category->title),
					'link'  => JRoute::_(Url::records($this->section, $this->category))
				];
			}
			else {
				$categories = JModelLegacy::getInstance('Categories', 'CobaltModel')->getParentsObjectsByChild($this->category->id);
				foreach($categories as $cat) {
					$path[] = [
						'title' => JText::_($cat->title),
						'link'  => Url::records($this->section, $cat)
					];
				}
			}
			$title .= ' - ' . $this->category->title;
		}
		//$path = array_reverse($path);
		$path[] = [
			'title' => $this->item->title,
			'link'  => ''
		];

		foreach($path as $item) {
			$pathway->addItem($item['title'], $item['link']);
		}
		
		$title .= ' - ' . $this->section->name;

		if($menu) {
			$this->appParams->def('page_heading', $title);

			if($menu->params->get('page_title')) {
				$title = $menu->params->get('page_title');
			}
		}

		// Check for empty title and add site name if param is set
		if(empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		if(empty($title)) {
			$title = $this->item->title;
		}
		$this->document->setTitle(strip_tags($title));

		foreach($this->document->_links as $lk => $dl) {
			if($dl['relation'] == 'canonical') {
				unset($this->document->_links[$lk]);
			}
		}
		$this->document->addHeadLink($this->item->canon, 'canonical');

		$meta['description'] = $this->section->params->get('more.metadesc');
		$meta['keywords']    = $this->section->params->get('more.metakey');
		if(empty($meta['author'])) {
			$meta['author'] = $this->section->params->get('more.author');
		}
		$meta['robots'] = $this->type->params->get('submission.robots', $this->section->params->get('more.robots'));
		if($this->item->meta_index) {
			$meta['robots'] = $this->item->meta_index;
		}
		if($this->print) {
			$meta['robots'] = 'noindex, nofollow';
		}

		if($this->category->id) {
			if($this->category->params->get('metadesc')) {
				$meta['description'] = $this->category->params->get('metadesc');
			}
			if($this->category->params->get('metakey')) {
				$meta['keywords'] = $this->category->params->get('metakey');
			}
			if($this->category->params->get('metadata.author')) {
				$meta['author'] = $this->category->params->get('metadata.author');
			}
			if($this->category->params->get('metadata.robots')) {
				$meta['robots'] = $this->category->params->get('metadata.robots');
			}
		}

		if($this->item->meta_descr) {
			$meta['description'] = $this->item->meta_descr;
		}
		elseif(!$this->item->meta_descr && $this->appParams->get('menu-meta_description')) {
			$meta['description'] = $this->appParams->get('menu-meta_description');
		}

		if($this->item->meta_key) {
			$meta['keywords'] = $this->item->meta_key;
		}
		elseif(!$this->item->meta_key && $this->appParams->get('menu-meta_keywords')) {
			$meta['keywords'] = $this->appParams->get('menu-meta_keywords');
		}

		MetaHelper::setMeta($meta);

		// If there is a pagebreak heading or title, add it to the page title
		if(!empty($this->item->page_title)) {
			$this->item->title = $this->item->title . ' - ' . $this->item->page_title;
			$this->document->setTitle($this->item->page_title . ' - ' . JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1));
		}
	}
}
