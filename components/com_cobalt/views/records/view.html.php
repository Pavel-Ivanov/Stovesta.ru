<?php
defined('_JEXEC') or die();

use Joomla\CMS\Object\CMSObject;

jimport('joomla.application.component.view');

class CobaltViewRecords extends JViewLegacy
{
    public string $baseurl;
    public CobaltModelRecords $model;
    public array $models;
    public ?CMSObject $section;
    public $category;
    public string $list_template;
    public array $list_templates;
    public array $tmpl_params;
    public array $submission_types;
    public ?array $items;
    public ?array $worns;
    public JPagination $pagination;
    public array $total_fields_keys;
    public array $sortable;
    public string $ordering;
    public string $ordering_dir;
    public int $total;
    public CMSObject $state;
    public array $posthere;
    public array $total_types;
    public array $total_types_option;
    public array $fields_keys_by_id;
    public array $filters;
    public bool $show_filters;
    public bool $show_category_index;
    public int $compare;
    public JUser $user;
    public JMenuItem $menu;
    public JInput $input;
    public JRegistry $appParams;
    public array $postbuttons;
    public string $action;
    public $title;
    public ?string $description;
    public bool $isSection;
    public bool $isCategory;

    public function display($tpl = null): void
    {
        $app = JFactory::getApplication();
		$this->user = JFactory::getUser();
//        $menus = $app->getMenu();
        $this->menu  = $app->getMenu()->getActive();

        $this->model = $this->getModel();
		$this->models['category'] = JModelLegacy::getInstance('Category', 'CobaltModel');
		$this->models['categories'] = JModelLegacy::getInstance('Categories', 'CobaltModel');
		$this->models['section'] = JModelLegacy::getInstance('Section', 'CobaltModel');
		$this->models['record'] = JModelLegacy::getInstance('Record', 'CobaltModel');

        // Section
        if(!$app->input->getInt('section_id')) {
            throw new RuntimeException(JText::_('CNOSECTION'), 404);
        }
        $this->section = ItemsStore::getSection($app->input->getInt('section_id'));
		if($this->section->published === '0') {
            $app->enqueueMessage(JText::_('CERR_SECTIONUNPUB'), 'message');
			$this->_redirect();
		}
		if(!$this->section->params->get('general.status', 1)) {
            $app->enqueueMessage(JText::_($this->section->params->get('general.status_msg'), 'message'));
            $this->_redirect();
		}
		if(!in_array($this->section->access, $this->user->getAuthorisedViewLevels())) {
            $app->enqueueMessage(JText::_('CERR_NOPAGEACCESS'));
            $this->_redirect();
		}

		// Category
		$category = $this->models['category']->getEmpty();
		if($app->input->getInt('cat_id')) {
			$category = ItemsStore::getCategory($app->input->getInt('cat_id'));
			if($r = $category->params->get('orderby')) {
				$this->section->params->set('general.orderby', $r);
			}
			if(NULL !== ($r = $category->params->get('records_mode'))) {
				$this->section->params->set('general.records_mode', $r);
			}
			if($r = $category->params->get('featured_first')) {
				$this->section->params->set('general.featured_first', $r);
			}
			if($r = $category->params->get('tmpl_markup')) {
				$this->section->params->set('general.tmpl_markup', $r);
			}
			$r = $category->params->get('tmpl_category');
			if($r || $r === '0') {
				$this->section->params->set('general.tmpl_category', $r);
			}
			if($r = $category->params->get('tmpl_compare')) {
				$this->section->params->set('general.tmpl_compare', $r);
			}
/*			if(!isset($category->id) {
				JError::raiseError(404, JText::_('CCATNOTFOUND'));
				$category = $this->models['category']->getEmpty();
			}*/
			if($category->id && ($category->section_id != $this->section->id)) {
                $app->enqueueMessage(JText::_('CCATWRONGSECTION'));
				$category = $this->models['category']->getEmpty();
			}
			if(!in_array($category->access, $this->user->getAuthorisedViewLevels())) {
                $app->enqueueMessage(JText::_($this->section->params->get('general.status_msg'), 'message'));
                throw new RuntimeException(JText::_($this->section->params->get('general.status_msg')), 403);
			}
		}
        $this->category = $category;

        if ($this->category->id === 0) {
            $this->isCategory = false;
        }
        else {
            $this->isCategory = true;
        }

		$itemid = (int) $category->params->get('category_itemid', $this->section->params->get('general.category_itemid'));
		if($itemid && $itemid != $app->input->getInt('Itemid')) {
			$app->redirect(JRoute::_(Url::records($this->section, ($category->id ? $category : NULL), NULL, NULL,
                ['start' => $app->input->getInt('start')]), false));
		}

		$this->_setupTemplates();
		$this->model->section = $this->section;

		$this->submission_types = $this->model->getAllTypes();
		ksort($this->submission_types);

		$this->model->types = $this->submission_types;

		$this->items = $this->get('Items');
		$item_ids = [];
		foreach($this->items as &$item) {
			$item = $this->models['record']->_prepareItem($item, 'list');
			$item_ids[] = $item->id;
		}

		JSession::getInstance('com_cobalt', [])->set('cobalt_last_list_ids', $item_ids);

		$state = $this->get('State');
		$this->worns = $this->get('Worns');

		$show_menu = TRUE;
		if($this->section->params->get('general.records_mode') === '0' && $this->section->params->get('general.filter_mode') === '1' && !count($this->items) && !count($this->worns)) {
			$show_menu = FALSE;
		}
		if(!$show_menu) {
			$this->tmpl_params['markup']->set('filters.filters', 0);
		}
		if($this->tmpl_params['markup']->get('filters.filters_home', 1) == 0 && !$this->isCategory) {
			$this->tmpl_params['markup']->set('filters.filters', 0);
		}
		if($this->tmpl_params['markup']->get('menu.menu_home', 1) == 0 && !$this->isCategory && !$app->input->get('view_what')) {
			$this->tmpl_params['markup']->set('menu.menu', 0);
		}

		$this->pagination = $this->get('Pagination');

		$this->total_fields_keys = $this->_fieldsSummary($this->items);
		$this->sortable          = CobaltModelRecord::$sortable;

		$field_orders = JFactory::getApplication()->getUserState("com_cobalt.records{$this->section->id}.ordering.vals{$this->section->id}");
		if(is_array($field_orders)) {
			$this->ordering = implode('^', $field_orders);
		}
		else {
			$this->ordering = $state->get('list.ordering');
		}
		$this->ordering_dir = $state->get('list.direction');

		$this->total    = $this->get('Total');
		$this->state    = $state;
		$this->posthere = [];

		$this->total_types = $this->model->getFilterTypes();

		$this->total_types_option[] = JText::_('CSELECTTYPE');
		foreach($this->total_types as $type_id) {
			$this->total_types_option[$type_id] = $this->submission_types[$type_id]->name;
		}
		//$this->total_types_ = $this->model->getTypes();
		$this->fields_keys_by_id = $this->model->getKeys($this->section);

//		$this->_prepareAlpha();
		$this->_prepareFilters();
		$this->_showCategoryIndex();
//		$this->_personalize();

		$list = $app->getUserState("compare.set{$this->section->id}");
		ArrayHelper::clean_r($list);
		$this->compare = count($list);

//		$this->isMe = (int)(($user->get('id') == $app->input->getInt('user_id')) && $user->get('id'));

//		$this->user = $user;

//		$this->menu  = $menus->getActive();
		$this->input = $app->input;

		$this->_prepareDocument();

		parent::display($tpl);
	}

	private function _redirect(): void
    {
		JFactory::getApplication()->redirect(JRoute::_('index.php?Itemid=' . $this->section->params->get('general.noaccess_redirect')));
	}

	private function _showFilters(): bool
    {
		$show = false;

		if($this->worns) {
			$show = true;
		}
		if($this->items) {
			$show = true;
		}
		if($this->section->params->get('general.filter_mode') === '0') {
			$show = true;
		}
		if($this->section->params->get('general.filter_mode') === '1' && $this->isCategory) {
			$show = true;
			if($this->section->params->get('general.records_mode') === '1' && !$this->worns && !$this->items) {
				$show = false;
			}
		}
		$this->show_filters = $show;
		return $show;
	}

	private function _showCategoryIndex(): bool
    {
		$app  = JFactory::getApplication();
		$show = true;

		if(!$this->section->params->get('general.tmpl_category')) {
			$show = FALSE;
		}
		if($this->section->params->get('general.filter_mode') == 0 && $this->worns) {
			$show = FALSE;
		}
		if($app->input->getString('view_what')) {
			$show = FALSE;
		}
		if($this->section->get('categories', '0') === '0') {
			$show = false;
		}
		if($this->category->params->get('tmpl_category') === 0) {
			$show = FALSE;
		}
		if(!$app->input->get('cat_id') && $this->worns && $this->section->params->get('general.section_home_items') == 0 && $this->section->params->get('general.filter_mode') == 1) {
			$show = FALSE;
		}
		$this->show_category_index = $show;

		return $show;
	}

	private function _prepareFilters(): void
    {
		$this->filters = [];

		if(!$this->_showFilters()) {
			return;
		}
		if(!$this->tmpl_params['markup']->get('filters.filters')) {
			return;
		}
		JHtml::_('behavior.framework');
		$filters = $this->get('Filters');

		$this->filters = $filters;
	}

	private function _prepareDocument(): void
    {
		$app             = JFactory::getApplication();
		$doc             = JFactory::getDocument();
		$menus           = $app->getMenu();
		$menu            = $menus->getActive();
		$this->appParams = $app->getParams();
		$pathway         = $app->getPathway();
		$markup          = $this->tmpl_params['markup'];

		$menupost = $this->submission_types;

		if(!$app->input->get('user_id') && !$app->input->get('view_what')) {
			foreach($doc->_links as $lk => $dl) {
				if($dl['relation'] === 'canonical') {
					unset($doc->_links[$lk]);
				}
			}

			$doc->addHeadLink(JRoute::_(Url::records($this->section, $this->category) .
				($app->input->get('start') ? '&start=' . $app->input->get('start') : NULL), true, 1), 'canonical');
		}

		$cattypes = $this->category->params->get('posttype', []);
		if($cattypes && $cattypes[0] !== '') {
			$menupost = [];
			foreach($cattypes as $ct) {
				if($ct === 'none') {
					break;
				}
				$menupost[] = $this->submission_types[$ct];
			}
		}
		$this->postbuttons = [];
		foreach($menupost as $menutype) {
			if($app->input->get('view_what') === 'children') {
				continue;
			}

			if(!in_array($this->tmpl_params['markup']->get('menu.menu_newrecord'), $this->user->getAuthorisedViewLevels())) {
				continue;
			}

			if(!in_array($this->tmpl_params['markup']->get('menu.menu_newrecord_home', $this->tmpl_params['markup']->get('menu.menu_newrecord')), $this->user->getAuthorisedViewLevels()) &&
                !$this->isCategory) {
				continue;
			}
			$this->postbuttons[] = $menutype;
		}

		$url = JFactory::getURI();
		$url->delVar('filter_order');
		$url->delVar('filter_order_Dir');
		$this->action = $url->toString();

		$t       = $path = [];
		if(count($this->worns)) {
			$search_strings = [];
			foreach($this->worns as $w) {
				$search_strings[] = $w->label . ': ' . $w->text;
			}
			$t[] = JText::_('CSEARCHRESULT') . ' (' . implode(',', $search_strings) . ')';
		}
		if($vw = $app->input->getString('view_what', FALSE)) {
			$t[] = JText::_('VW_' . strtoupper($vw));
		}

		if($this->isCategory) {
			$t[] = Mint::_($this->category->title);
		}
		$t[] = $this->section->name;

		if($this->pagination->pagesCurrent > 1) {
			$t[] = JText::_('CPAGE').' ' . $this->pagination->pagesCurrent;
		}

		$head_title = implode(' - ', $t);
		if($menu) {
			$this->appParams->def('page_heading', $head_title);
			if($menu->params->get('page_title')) {
				$head_title = $menu->params->get('page_title');
			}
			if ($this->pagination->pagesCurrent > 1) {
				$head_title .= ' - Страница ' . $this->pagination->pagesCurrent;
			}
		}

		if(empty($head_title)) {
			$head_title = $app->get('sitename');
		}
		elseif($app->get('sitename_pagetitles', 0) == 1) {
			$head_title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $head_title);
		}
		elseif($app->get('sitename_pagetitles', 0) == 2) {
			$head_title = JText::sprintf('JPAGETITLE', $head_title, $app->get('sitename'));
		}

		$doc->setTitle(strip_tags($head_title));

		switch($markup->get('title.title_show')) {
			case 1:
				$t = [];
				if($this->isCategory && $markup->get('title.title_category_name')) {
					$t[] = JText::_($this->category->title);
				}
				if($markup->get('title.title_section_name') || !$this->isCategory) {
					$t[] = $this->section->name;
				}
				$title = implode(' - ', $t);
				break;
			case 2:
				$title = $menu->params->get('page_title');
				break;
			case 3:
				$title = $markup->get('title.title_static', 'This is static title in markup template parameters');
				break;
		}

		if($app->input->get('page_title')) {
			$title = CobaltFilter::base64(urldecode($app->input->get('page_title')));
		}

		$this->title = @$title;

        if($this->isCategory) {
            if($this->category->parent_id == 1) {
                $path[] = array(
                    'title' => JText::_($this->category->title),
                    'link'  => ''
                );
            }
            else {
                $categories = $this->models['categories']->getParentsObjectsByChild($this->category->id);
                foreach($categories as $cat) {
                    array_unshift($path,
                        array(
                            'title' => JText::_($cat->title),
                            'link'  => Url::records($this->section, $cat)
                        ));
                }
            }
        }

		if(!$app->input->getInt('Itemid', FALSE)) {
			$path[] = [
				'title' => $this->section->name,
				'link'  => JRoute::_(Url::records($this->section))
			];
		}
		$path = array_reverse($path);

		foreach($path as $item) {
			$pathway->addItem($item['title'], $item['link']);
		}

        //$this->description
		$description = NULL;
		if($markup->get('main.description_mode') && !$vw) {
			$description = $this->section->{'descr_' . $markup->get('main.description_mode', 'full')};

			if($this->isCategory) {
				$description = $this->category->{'descr_' . $markup->get('main.description_mode', 'full')};
			}

			if($markup->get('tmpl_core.description_html') && $description) {
				$description = '<p>' . strip_tags($description) . '</p>';
			}
		}
		$this->description = $description;

		// Set META
		$meta = [];
		if($this->isCategory) {
			$meta['description'] = $this->category->get('metadesc');
			$meta['keywords'] = $this->category->get('metakey');
			$meta['author'] = $this->category->get('metadata.author');
			$meta['robots'] = $this->category->get('metadata.robots');
		}
        else {
            $meta['description'] = $this->section->params->get('more.metadesc');
            $meta['keywords']    = $this->section->params->get('more.metakey');
            $meta['author']      = $this->section->params->get('more.author');
            $meta['robots']      = $this->section->params->get('more.robots');
        }
        MetaHelper::setMeta($meta);
    }

	public function _fieldsSummary($items): array
    {
		$fields = $byid = $sort = [];
		foreach($items as $item) {
			foreach($item->fields_by_id as $field) {
				$key              = $field->key;
				$field->sortby    = sprintf('%d.%d', $field->group_order, $field->ordering);
				$fields[$key]     = $field;
				$sort[$key]       = $field->sortby;
				$byid[$field->id] = $key;
			}
		}
		natsort($sort);

		if($sort) {
			$result = [];
			foreach($sort as $key => $value) {
				$result[$key] = $fields[$key];
			}
			$fields = $result;
		}
		return $fields;
	}

	private function _setupTemplates(): void
    {
//		$doc  = JFactory::getDocument();
		$app  = JFactory::getApplication();
//		$sess = JFactory::getSession();

		$dir = JPATH_ROOT . '/components/com_cobalt/views/records/tmpl/';

		if($this->section->params->get('general.tmpl_category') && $this->section->get('categories', '0')) {
			$tmpl_params['cindex'] = CTmpl::prepareTemplate('default_cindex_', 'general.tmpl_category', $this->section->params);
		}

		// setup markup template
		$tmpl_params['markup'] = CTmpl::prepareTemplate('default_markup_', 'general.tmpl_markup', $this->section->params);

		$key = $this->section->id;

		$this->list_templates = $this->_getTemplatesNames($this->section->params->get('general.tmpl_list', 'default'));
		$default_tmpl         = $this->section->params->get('general.tmpl_list_default');

		$cat_tmpl = $this->category->params->get('tmpl_list');
		ArrayHelper::clean_r($cat_tmpl);
		if(!empty($cat_tmpl)) {
			$key .= '-' . $this->category->id;
			$this->list_templates = $this->_getTemplatesNames($cat_tmpl);
			$default_tmpl         = $this->category->get('tmpl_list_default', $default_tmpl);
		}

		$ak               = array_keys($this->list_templates);
		$default_template = array_shift($ak);
		@list($tmp_name, $tmp_key) = explode('.', $default_template);

		if($default_tmpl && is_array($this->list_templates) && array_key_exists($default_tmpl . '.' . $tmp_key, $this->list_templates)) {
			$default_template = $default_tmpl . '.' . $tmp_key;
		}

		$name = JFactory::getApplication()->getUserState("com_cobalt.section{$key}.filter_tpl", $default_template);

		$tmpl = explode('.', $name);
		$tmpl = $tmpl[0];

		if(!JFile::exists("{$dir}default_list_{$tmpl}.php")) {
			$name = 'default';
		}

		$this->section->params->set('general.tmpl_list', $name);
		$lparams = CTmpl::prepareTemplate('default_list_', 'general.tmpl_list', $this->section->params);

		$def_limit = $lparams->get('tmpl_params.leading', 0);
		$def_limit += $lparams->get('tmpl_params.blog_intro', 0);
		$def_limit += $lparams->get('tmpl_params.blog_links', 0);

		if($def_limit) {
			$limit = $def_limit;
		}
		else {
			$limit = $app->getUserStateFromRequest('cobalt' . $key . '.limit', 'limit');
			if(!$limit)
			{
				$limit = $lparams->get('tmpl_core.item_limit_default', JFactory::getConfig()->get('list_limit', 20));
				$app->setUserState('cobalt' . $key . '.limit', $limit);
			}
		}

		$app->setUserState('global.list.limit', $limit);

		$tmpl_params['list'] = $lparams;

		if($tmpl_params['markup']->get('menu.menu_templates_sort')) {
			ksort($this->list_templates);
		}

		$this->list_template = $this->section->params->get('general.tmpl_list', $name);
		$this->tmpl_params   = $tmpl_params;
	}

	private function _getTemplatesNames($list): array
    {
		$list = (array)$list;
		ArrayHelper::clean_r($list);

		$out = [];
		foreach($list as $template) {
			$tmpl = explode('.', $template);
			$tmpl = $tmpl[0];

			$path = JPATH_ROOT . '/components/com_cobalt/views/records/tmpl/default_list_' . $tmpl . '.xml';
			if(!JFile::exists($path)) {
				JError::raiseNotice(404, 'Template XML file not found: ' . $path);
				return $out;
			}
			$xml = simplexml_load_string(file_get_contents($path));
			$out[$template] = $xml->name;
		}
		return $out;
	}
}
