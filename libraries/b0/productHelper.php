<?php
function setImageAccessory($item, $defaultPicture)
{
	if (isset($item->fields_by_id[AccessoryIds::ID_IMAGE]->result)){
		return [
			'url' => JUri::base() . $item->fields_by_id[AccessoryIds::ID_IMAGE]->value['image'],
			'width' => $item->fields_by_id[AccessoryIds::ID_IMAGE]->params->get('params.thumbs_width', '400') . 'px',
			'height' => $item->fields_by_id[AccessoryIds::ID_IMAGE]->params->get('params.thumbs_height', '300') . 'px',
			'result' => $item->fields_by_id[AccessoryIds::ID_IMAGE]->result ?? '',
		];
	}
	return [
		'url' => $defaultPicture,
		'width' => '400px',
		'height' => '300px',
		'result' => '<img src="' . $defaultPicture . '" width="400" height="300" alt="' . $item->title . '" title="' . $item->title . '">'
	];
}

function setVideoAccessory($item, $defaultVideo)
{
	if (isset($item->fields_by_id[AccessoryIds::ID_VIDEO]->result)){
		return [
			'url' => $item->fields_by_id[AccessoryIds::ID_VIDEO]->raw['link'][0],
			'width' => $item->fields_by_id[AccessoryIds::ID_VIDEO]->params->get('params.default_width', '400') . 'px',
			'height' => '300px',
			'result' => '<div class="uk-cover"><iframe src="' . $item->fields_by_id[AccessoryIds::ID_VIDEO]->raw['link'][0] . '" width="400" height="300" frameborder="0" allowfullscreen></iframe></div>',
		];
	}
	return [
		'url' => $defaultVideo,
		'width' => '400px',
		'height' => '300px',
		'result' => '<img src="' . $defaultVideo . '" width="400" height="300" alt="' . $item->title . '" title="' . $item->title . '">'
	];
}

function setImageSparepart($item, $defaultPicture)
{
	if (isset($item->fields_by_id[SparepartIds::ID_IMAGE]->result)){
		return [
			'url' => JUri::base() . $item->fields_by_id[SparepartIds::ID_IMAGE]->value['image'],
			'width' => $item->fields_by_id[SparepartIds::ID_IMAGE]->params->get('params.thumbs_width', '400') . 'px',
			'height' => $item->fields_by_id[SparepartIds::ID_IMAGE]->params->get('params.thumbs_height', '300') . 'px',
			'result' => $item->fields_by_id[SparepartIds::ID_IMAGE]->result ?? '',
		];
	}
	return [
		'url' => $defaultPicture,
		'width' => '400px',
		'height' => '300px',
		'result' => '<img src="' . $defaultPicture . '" width="400" height="300" alt="' . $item->title . '" title="' . $item->title . '">'
	];
}

function setVideoSparepart($item, $defaultVideo)
{
	if (isset($item->fields_by_id[SparepartIds::ID_VIDEO]->result)){
		return [
			'url' => $item->fields_by_id[SparepartIds::ID_VIDEO]->raw['link'][0],
			'width' => $item->fields_by_id[SparepartIds::ID_VIDEO]->params->get('params.default_width', '400') . 'px',
			'height' => '300px',
			'result' => '<div class="uk-cover"><iframe src="' . $item->fields_by_id[SparepartIds::ID_VIDEO]->raw['link'][0] . '" width="400" height="300" frameborder="0" allowfullscreen></iframe></div>',
		];
	}
	return [
		'url' => $defaultVideo,
		'width' => '400px',
		'height' => '300px',
		'result' => '<img src="' . $defaultVideo . '" width="400" height="300" alt="' . $item->title . '" title="' . $item->title . '">'
	];
}
