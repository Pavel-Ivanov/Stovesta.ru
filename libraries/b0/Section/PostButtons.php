<?php
defined('_JEXEC') or die;

class PostButtons
{
	public string $urlPostButtons;

	public function __construct(object $section, object $category, $postButtons)
	{
		$submit = array_values($postButtons);
		$submit = array_shift($submit);
		$this->urlPostButtons = Url::add($section, $submit, $category);
	}
}
