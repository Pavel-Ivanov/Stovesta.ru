<?php
defined('_JEXEC') or die;

class Section
{
	public bool $isSection;
	public bool $isCategory;
	public string $title;
	public string $subTitle;
	public string $metaTitle;
	public string $metaDescription;
	public string $imageSource;
	public string $modulePopularWorks;

	public function __construct(object $section, object $paramsMarkup, object $category, int $pageCurrent)
	{
		if ($category->id) {
			$this->isSection = false;
			$this->isCategory = true;
			$this->title = $section->name . ' ' . $category->title;
			$this->subTitle = $category->description;
			$this->metaTitle = $category->metakey . ($pageCurrent > 1 ? ' - Страница ' . $pageCurrent : '');
			$this->metaDescription = $category->metadesc . ($pageCurrent > 1 ? ' - Страница ' . $pageCurrent : '');
			$this->imageSource = $category->image;
		}
		else {
			$this->isSection = true;
			$this->isCategory = false;
			$this->title = $section->title;
			$this->subTitle = $section->description;
			$this->metaTitle = $section->params->get('more.metakey');
			$this->metaDescription = $section->params->get('more.metadesc');
			$this->imageSource = $paramsMarkup->get('main.section_icon');
		}
		$this->modulePopularWorks = $paramsMarkup->get('main.module_popular');
	}
}
