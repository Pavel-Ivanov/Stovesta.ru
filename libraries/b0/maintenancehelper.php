<?php

function thead_benzin()
{
	echo '
<thead>
    <tr>
        <th>Мотор</th>
        <th class="uk-text-center">15000</th>
        <th class="uk-text-center">30000</th>
        <th class="uk-text-center">45000</th>
        <th class="uk-text-center">60000</th>
        <th class="uk-text-center">75000</th>
        <th class="uk-text-center">90000</th>
    </tr>
</thead>';
}
function thead_tr_benzin()
{
	echo
	'<tr>
        <th>Мотор</th>
        <th class="uk-text-center">15000</th>
        <th class="uk-text-center">30000</th>
        <th class="uk-text-center">45000</th>
        <th class="uk-text-center">60000</th>
        <th class="uk-text-center">75000</th>
        <th class="uk-text-center">90000</th>
    </tr>';
}

function thead_dizel()
{
	echo '
<thead>
    <tr>
        <th>Мотор / Привод</th>
        <th class="uk-text-center">10000</th>
        <th class="uk-text-center">20000</th>
        <th class="uk-text-center">30000</th>
        <th class="uk-text-center">40000</th>
        <th class="uk-text-center">50000</th>
        <th class="uk-text-center">60000</th>
        <th class="uk-text-center">70000</th>
        <th class="uk-text-center">80000</th>
        <th class="uk-text-center">90000</th>
    </tr>
</thead>';
}

function thead_tr_dizel()
{
	echo
	'<tr>
        <th>Мотор / Привод</th>
        <th class="uk-text-center">10000</th>
        <th class="uk-text-center">20000</th>
        <th class="uk-text-center">30000</th>
        <th class="uk-text-center">40000</th>
        <th class="uk-text-center">50000</th>
        <th class="uk-text-center">60000</th>
        <th class="uk-text-center">70000</th>
        <th class="uk-text-center">80000</th>
        <th class="uk-text-center">90000</th>
    </tr>';
}

function tbody_tr($items) {
	echo '<tr class="uk-table-middle">';
	echo '<th>' . $items['th'] . '</th>';
	foreach ($items['items'] as $item) {
		echo '<td class="uk-text-center">';
		$toNum = $item['milage'] / $items['freq'];
		$title = 'Техническое обслуживание '.$items['model'].' '.$items['motor'].' '.$items['years'].' пробег '.$item['milage']. ' км (ТО-'.$toNum.')';
		echo '<a href="'.$item['tdHref'].'" title="'.$title.'" target="_blank">';

		switch ($item['type']) {
			case 'oil':
				$imgAlt = 'Замена масла и воздушного фильтра';
				break;
			case 'oil-ign':
				$imgAlt = 'Замена масла, воздушного фильтра и свечей зажигания';
				break;
			case 'oil-ign-grm':
				$imgAlt = 'Замена масла, воздушного фильтра, свечей зажигания и комплекта ГРМ';
				break;
			case 'oil-ign-liq':
				$imgAlt = 'Замена масла, воздушного фильтра, свечей зажигания и технических жидкостей';
				break;
			case 'oil-ign-grm-liq':
				$imgAlt = 'Замена масла, воздушного фильтра, свечей зажигания, комплекта ГРМ и технических жидкостей';
				break;
			default:
				$imgAlt = '';
		}
		$imgSrc = '/images/elements/maintenance/'.$item['type'].'.png';
		echo '<img src="'.$imgSrc.'" alt="'.$imgAlt.'" width="64" height="32">';
		echo '</a>';
		echo '</td>';
	}
	echo '</tr>';
}
