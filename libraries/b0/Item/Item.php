<?php
defined('_JEXEC') or die;

class Item
{
	public const TIME_FORMAT = 'l, d F Y';

	public $user;
	// Core
	public $siteName;
	public $title;
	public $url;
	public $hits;
//	public $rating;
	public $cTime;
	public $mTime;
	public $controls;
	public $userAutorizedLevels;

	protected $iconRub = '<i class="uk-icon-rub uk-text-muted uk-margin-left"></i>';

	public function __construct($item, $user = null)
	{
		//$this->user = $user;
		$this->siteName = JFactory::getApplication()->get('sitename');
		$this->title = $item->title;
		$this->url = JRoute::_($item->url, TRUE, 1);
		$this->hits = $item->hits;
//		$this->rating = $item->rating;
		$this->cTime = $item->ctime;
		$this->mTime = $item->mtime;
		$this->controls = !empty($item->controls) ? $item->controls : null;
		$this->userAutorizedLevels = $user ? $user->getAuthorisedViewLevels() : null;
	}

	protected function setField($field, $userAuthorisedViewLevels)
	{
		if (!$field) {
			return null;
		}
		//$access = (bool) in_array($field->params->get('core.field_view_access'), $userAuthorisedViewLevels);
		$item = new JRegistry(['label' => $field->label,
		                       'value' => $field->raw,
		                       'result' => $field->result,
		                       'access' => (bool) in_array($field->params->get('core.field_view_access'), $userAuthorisedViewLevels)]);
		return $item;
	}

	public function renderTitle($tag = 'h1')
	{
		echo '<'.$tag.' class="uk-text-center-small">' . $this->title . '</'.$tag.'>';
	}

	public function renderField($field, $user, $meta='')
	{
		if (isset($field) && in_array($field->params->get('core.field_view_access'), $user->getAuthorisedViewLevels())) {
			echo '<p>';
			echo '<strong>'.$field->label.': </strong>'.$field->result;
			echo '</p>';
			if ($meta) {
				echo JMicrodata::htmlMeta(strip_tags($field->result), $meta);
			}
		}

	}
	public function renderHits() {
		echo '<i class="uk-icon-eye"></i><em> Просмотров: </em>'.$this->hits;
	}

	public function renderCTime() {
		echo $this->cTime->format(Item::TIME_FORMAT);
	}

	public function renderMTime() {
		echo $this->mTime->format(Item::TIME_FORMAT);
	}

	public function renderControls()
	{
		$out = '';
		foreach($this->controls as $key => $link) {
			if(is_array($link))
			{
				$out .= '<li>' . $key;
//				$out .= '<ul class="dropdown-menu">';
//				$out .= list_controls($link);
//				$out .= "</ul>";
				$out .= "</li>";
			}
			else
			{
				$out .= "<li>{$link}</li>";
			}
		}
		echo $out;
	}
}
