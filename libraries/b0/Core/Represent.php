<?php
defined('_JEXEC') or die;
JImport('b0.Core.RepresentKeys');

trait Represent
{
	public $image;
	public $description;
	public $links;
	public $gallery;
	public $video;

	private function setImage($fields)
	{
		if (!isset($fields[RepresentKeys::IMAGE_KEY])){
			$this->image = null;
			return;
		}
		$this->image = [
			'url' => isset($fields[RepresentKeys::IMAGE_KEY]) ? JUri::base() . $fields[RepresentKeys::IMAGE_KEY]->value['image'] : '',
			'result' => $fields[RepresentKeys::IMAGE_KEY]->result ?? '',
            'height' => $fields[RepresentKeys::IMAGE_KEY]->params->get('params.thumbs_height') ?? '',
            'width' => $fields[RepresentKeys::IMAGE_KEY]->params->get('params.thumbs_width') ?? '',
		];
	}

	private function setDescription($fields)
	{
		$this->description = $fields[RepresentKeys::DESCRIPTION_KEY]->result ?? null;
	}

	private function setLinks($fields)
	{
		$this->links = $fields[RepresentKeys::LINKS_KEY]->result ?? null;
	}

	private function setGallery($fields)
	{
		$this->gallery = $fields[RepresentKeys::GALLERY_KEY]->result ?? null;
		if (!isset($fields[RepresentKeys::GALLERY_KEY]->result)){
			$this->gallery = null;
			return;
		}
		$gallery['result'] = $fields[RepresentKeys::GALLERY_KEY]->result;
		$gallery['baseUrl'] = JUri::base() . JComponentHelper::getParams('com_cobalt')->get('general_upload') . '/' .$fields[RepresentKeys::GALLERY_KEY]->params->get('params.subfolder');
		$gallery['url'] = [];
		foreach ($fields[RepresentKeys::GALLERY_KEY]->value as $link) {
			array_push($gallery['url'], $link['fullpath']);
		}
		$this->gallery = $gallery;

	}

	private function setVideo($fields)
	{
		if (!isset($fields[RepresentKeys::VIDEO_KEY]->result)){
			$this->video = null;
			return;
		}
		$video['result'] = $fields[RepresentKeys::VIDEO_KEY]->result;
		$video['url'] = [];
		foreach ($fields[RepresentKeys::VIDEO_KEY]->value['link'] as $link) {
			array_push($video['url'], $link);
		}
		$this->video = $video;
	}

	private function setRepresent($fields)
	{
		$this->setImage($fields);
		$this->setDescription($fields);
		$this->setLinks($fields);
		$this->setGallery($fields);
		$this->setVideo($fields);
	}

	public function renderImage()
	{
		if (!$this->image) {
			return;
		}
		echo $this->image['result'];
	}

	public function renderDescription()
	{
		if (!$this->description) {
			return;
		}
		echo '<div class="uk-margin-top uk-margin-large-bottom">';
		echo '<hr class="uk-article-divider">';
		echo $this->description;
		echo '</div>';
	}

	public function renderGallery()
	{
		if (!$this->gallery) {
			return;
		}
		echo '<div class="uk-margin-top uk-margin-large-bottom">';
		echo '<hr class="uk-article-divider">';
		echo $this->gallery['result'];
		echo '</div>';
	}

	public function renderLinks()
	{
		if (!$this->links) {
			return;
		}
		echo '<div class="uk-margin-top uk-margin-large-bottom">';
		echo '<hr class="uk-article-divider">';
		echo '<h2 class="uk-article-title">Рекомендуем прочитать</h2>';
		echo $this->links;
		echo '</div>';
	}

	public function renderVideo()
	{
		if (!$this->video) {
			return;
		}
		echo '<div class="uk-margin-top uk-margin-large-bottom">';
		echo '<hr class="uk-article-divider">';
		echo $this->video['result'];
		echo '</div>';
	}
}