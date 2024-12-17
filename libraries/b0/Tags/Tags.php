<?php
defined('_JEXEC') or die;

JImport('b0.Tags.TagsConfig');
class Tags
{
    public array $tags = [];

    public function __construct($sectionId, $filters)
    {
        $db = JFactory::getDBO();
        // получаем теги, которые соответствуют текущему разделу

        $sql = "SELECT t.tag, t.slug, t.id, rc.catid FROM #__js_res_tags AS t
		LEFT JOIN #__js_res_tags_history AS h ON h.tag_id = t.id
		LEFT JOIN #__js_res_record AS r ON r.id = h.record_id
		LEFT JOIN #__js_res_record_category AS rc ON rc.record_id = h.record_id
		WHERE r.published = 1
		GROUP BY t.id
		ORDER BY t.id";

        if(TagsConfig::LIMIT > 0) {
            $sql .= ' LIMIT 0, ' . TagsConfig::LIMIT;
        }
//        b0debug($sql);
        $db->setQuery($sql);

        $tags = $db->loadObjectList();
//        b0dd($tags);
        if(!$tags) {
            $this->tags = [];
        }

        $list = [];
        foreach($tags as $tag) {
            $list[$tag->id] = new stdClass();
            $list[$tag->id]->tag = $tag->tag;
	        $list[$tag->id]->isActive = false;
        }
        // $list - какие теги есть в этой категории
//        b0debug($list);

//        $order = TagsConfig::ORDERING;

        $query = $db->getQuery(true);
        $query->select('t.tag, t.id');
        $query->select('(SELECT COUNT(*) FROM #__js_res_tags_history WHERE tag_id = t.id) as r_usage');
        $query->select('(SELECT SUM(hits) FROM #__js_res_tags_history WHERE tag_id = t.id) as hits');
        $query->from('#__js_res_tags AS t');
        $query->where('t.id IN (' . implode(', ', array_keys($list)) . ')');
//        $query->order($order);

        $db->setQuery($query);
        $res = $db->loadObjectList();
//        b0debug($res);
/*        if($order != 'RAND()') {
            $list = null;
        }*/

        $nums = [];
        foreach($res as $val) {
/*            if($order != 'RAND()') {
                $list[$val->id] = new stdClass();
                $list[$val->id]->tag = $val->tag;
            }*/
            $list[$val->id]->hits = $val->hits;
            $list[$val->id]->r_usage = $val->r_usage;

/*			switch($params->get('item_tag_num', 0)) {
                case '1':
                    $nums[$val->id] = array('rel' => "tooltip", 'data-original-title' => JText::_('CTAGHITS') . ': ' . $val->hits);
                break;
                case '2':
                    $nums[$val->id] = array('rel' => "tooltip", 'data-original-title' => JText::_('CTAGUSAGE') . ': ' . $val->r_usage);
                break;
                case '3':
                    $nums[$val->id] = array('rel' => "tooltip", 'data-original-title' => JText::_('CTAGHITS') . ': ' . $val->hits . ', ' . JText::_('CTAGUSAGE') . ': ' . $val->r_usage);
                break;
            }*/
            $nums[$val->id] = [
                'rel' => "tooltip",
                'data-original-title' => "Просмотров: {$val->hits}, Элементов: {$val->r_usage}"
            ];
        }
        if (isset($filters['tags'])) {
            $activeTagId = $filters['tags']->value[0];
            foreach ($list as $id => $item) {
                if ($id == $activeTagId) {
                    $item->isActive = true;
                }
                else {
                    $item->isActive = false;
                }
            }
        }

        $link = "index.php?option=com_cobalt&task=records.filter&section_id={$sectionId}&filter_name[0]=filter_tag";
        $out = [];
        foreach($list as $id => $item) {
//			$item->html = JHtml::link(JRoute::_($link . '&filter_val[0]=' . $id), $item->tag, ($params->get('item_tag_num', 0) ? @$nums[$id] : NULL));
            $item->html = JHtml::link(JRoute::_($link . '&filter_val[0]=' . $id), $item->tag, 'style="color:#666;"');
            $out[] = $item;
        }
//        b0debug($out);
        $this->tags = $out;
    }

    public function tagsRender()
    { ?>
        <div data-uk-button-radio>
            <?php foreach ($this->tags as $id => $tag): ?>
                <button class="uk-button uk-button-primary"><?= $tag->html ?></button>
            <?php endforeach; ?>

        </div>
    <?php }

    public function tagsRenderAsBage()
    {
        foreach ($this->tags as $tag) : ?>
            <div class="uk-badge uk-margin-right anchor-tags"
                 style="text-transform: none; background: #f6f6f6; border: none; font-size: 16px; color: #666; <?= $tag->isActive ? 'font-weight: bold;' : ''?>">
                <?= $tag->html ?>
            </div>
        <?php endforeach; ?>

<!--        <button class="uk-close uk-text-danger" style="opacity: 1.0" onclick="Joomla.submitbutton('records.cleanall')" title="Сбросить все фильтры"></button>-->
    <?php }

    public function tagsRenderAsCheckbox()
    {
        b0debug($this->tags);?>
        <div class="uk-form-row">
            <div class="uk-form-controls">
                <?php foreach ($this->tags as $tag) : ?>
                    <input type="checkbox" class="uk-margin-left" name="filters[filter_tag][value][]" value="<?= $tag->tag ?>">
                <?php endforeach; ?>
            </div>
        </div>
    <?php }
}
