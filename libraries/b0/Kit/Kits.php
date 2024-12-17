<?php
defined('_JEXEC') or die;

JImport('b0.Kit.KitIds');
JImport('b0.Kit.KitsConfig');
class Kits
{
    public array $kits = [];

    public function __construct($sectionId, $categoryId, $filters)
    {
        $kitSectionId = KitIds::ID_KITS_SECTION;
        $db = JFactory::getDBO();

        $sql = "SELECT rc.catid, rc.record_id, rc.section_id, r.title, r.alias FROM #__js_res_record_category AS rc
        LEFT JOIN #__js_res_record AS r ON r.id = rc.record_id
		WHERE (rc.section_id=$kitSectionId AND rc.catid=$categoryId AND rc.published = 1)";

        if(KitsConfig::LIMIT > 0) {
            $sql .= ' LIMIT 0, ' . KitsConfig::LIMIT;
        }
        $db->setQuery($sql);
        $kits = $db->loadObjectList();
        if(!$kits) {
            $this->kits = [];
        }

        $out = [];
        foreach($kits as $kit) {
            $link = JRoute::_("catalog/item/".$kit->record_id.'-'.$kit->alias);
            $kit->html = '<a href="'.$link.'" target="_blank" style="color:#666;">' . $kit->title . '</a>';
            $out[] = $kit;
        }
        $this->kits = $out;
    }

    public function kitsRender()
    {
        foreach ($this->kits as $kit) : ?>
            <div class="uk-badge uk-margin-right anchor-tags uk-margin-bottom"
                 style="text-transform: none; background: #f6f6f6; border: none; font-size: 16px; color: #666;">
                <?= $kit->html ?>
            </div>
        <?php endforeach; ?>
    <?php }
}
