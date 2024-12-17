<?php
/** @var array $displayData
 * $displayData['href'] - Url ссылки на группу ВКонтакте
 *  $displayData['src'] - путь к иконке ВКонтакте
 */
defined('_JEXEC') or die();
if (!array_key_exists('href', $displayData)){
    return;
}
if (!array_key_exists('src', $displayData)){
    return;
}
?>
<p class="uk-h4">
    <a href="<?= $displayData['href'] ?>" target="_blank" rel="noopener" title="Обсудить или задать вопрос в нашей группе ВКонтакте">
        <img src="<?= $displayData['src'] ?>" width="24" height="24" style="width: 24px;" class="uk-margin-right" alt="">
        Обсудить или задать вопрос
    </a>
</p>
