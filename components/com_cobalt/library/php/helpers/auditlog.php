<?php
defined('_JEXEC') or die();

class ATlog
{
	public const REC_NEW = 1; //!
	public const REC_EDIT = 2; //!
	public const REC_DELETE = 3; //!
	public const REC_PUBLISHED = 4; //!
	public const REC_UNPUBLISHED = 5; //!
	public const REC_PROLONGED = 6; //!
	public const REC_FEATURED = 7; //!
	public const REC_HIDDEN = 8; //!
	public const REC_UNHIDDEN = 9; //!
	public const REC_ARCHIVE = 10; //!
	public const REC_TAGDELETE = 12; //!
	public const REC_ROLLEDBACK = 19; //!
	public const REC_RESTORED = 20; //!
	public const REC_VIEW = 26; //!
	public const REC_TAGNEW = 25; //!
	public const REC_FILE_DELETED = 27; //!
	public const REC_FILE_RESTORED = 28; //!
	public const REC_UNFEATURED = 29; //!
	public const REC_IMPORT = 30; //!
	public const REC_IMPORTUPDATE = 32; //!

	public const FLD_STATUSCHANGE = 13; //!

	public const COM_NEW = 14; //!
	public const COM_DELET = 15; //!
	public const COM_EDIT = 16; //!
	public const COM_PUBLISHED = 17; //!
	public const COM_UNPUBLISHED = 18; //!

	public static function log($record, $event, $comment_id = 0, $field_id = 0): void
	{
		if(is_int($record)) {
			$record_id = $record;
			$record = JTable::getInstance('Record', 'CobaltTable');
			$record->load($record_id);
		}
		if(is_object($record)) {
			if(method_exists($record, 'getProperties')) {
				$record = $record->getProperties();
			}
			else {
				$record = get_object_vars($record);
			}
		}
		$type = ItemsStore::getType($record['type_id']);

		unset($record['access_key'],$record['published'],$record['params'],$record['access'],$record['checked_out'],$record['checked_out_time'],
		$record['hits'],$record['ordering'],$record['meta_descr'],$record['meta_index'],$record['meta_key'],
		$record['alias'],$record['featured'],$record['archive'],$record['ucatid'],$record['ucatname'],$record['langs'],
		$record['asset_id'],$record['votes'],$record['favorite_num'],$record['hidden'],$record['votes_result'],$record['exalert'],
		$record['fieldsdata'],$record['fields'],$record['comments'],$record['tags'],$record['multirating'],
		$record['subscriptions_num'],$record['parent_id'],$record['parent'],$record['whorepost'],$record['repostedby']);

		if(!$type->params->get('audit.audit_log')) {
			return;
		}

		if(!$type->params->get('audit.al'.$event.'.on')) {
			return;
		}

		$log = JTable::getInstance('Audit_log', 'CobaltTable');

		$record['type_name'] = $type->name;
		$record['section_name'] = ItemsStore::getSection($record['section_id'])->name;
		if(!empty($record['categories']) && is_string($record['categories'])) {
			$record['categories'] = json_decode($record['categories']);
		}

		$data = [
			'record_id' => $record['id'],
			'type_id' => $record['type_id'],
			'section_id' => $record['section_id'],
			'comment_id' => $comment_id,
			'field_id' => $field_id,
			'ctime' => JFactory::getDate()->toSql(),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'user_id' => JFactory::getUser()->get('id', 0),
			'event' => $event,
			'params' => json_encode($record, JSON_THROW_ON_ERROR)
		];

		$log->save($data);
	}
}
