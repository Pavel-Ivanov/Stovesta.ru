<?php

/**
 * @param object      $item
 * @param object|null $itemImage
 * @param object|null $itemGallery
 * @param object|null $itemVideo
 *
 * @return array
 *
 * @since version
 */
function setOpenGraph(object $item, object $itemImage = null, object $itemGallery=null, object $itemVideo=null):array
{
	//$openGraph = [];
	$openGraph = [
		'og:type' => 'article',
		'og:title' => $item->title,
		'og:url' => JRoute::_($item->url, TRUE, 1),
		'og:description' => $item->meta_descr,
		'og:site_name' => JFactory::getApplication()->get('sitename'),
		'og:locale' => 'ru_RU',
	
	];
	
	$ogImages = [];
	if (isset($itemImage->raw)) {
		$ogImages[] = [
			'og:image' => $itemImage->raw['image'],
			'og:image:secure_url' => $itemImage->raw['image'],
			'og:image:type' => 'image/jpeg',
			'og:image:width' => '400',
			'og:image:height' => '300',
		];
	}
	
	if (isset($itemGallery->raw)) {
		foreach ($itemGallery->raw as $image) {
			$ogImages[] = [
				'og:image' => 'images/gallery/' . $image['fullpath'],
				'og:image:secure_url' => 'images/gallery/' . $image['fullpath'],
				'og:image:type' => 'image/' . $image['ext'],
				'og:image:width' => $image['width'],
				'og:image:height' => $image['height']
			];
		}
	}
	$openGraph += [
		'og:image' => $ogImages
	];
	
	if (isset($itemVideo->raw)) {
		$videos = [];
		foreach ($itemVideo->raw as $link) {
			$videos[] = [
				'og:video' => $link[0],
				'og:video:secure_url' => $link[0],
				'og:video:type' => 'video/mp4',
				'og:video:width' => '640',
				'og:video:height' => '480'
			];
		}
		$openGraph += [
			'og:video' => $videos
		];
	}
	return $openGraph;
}
