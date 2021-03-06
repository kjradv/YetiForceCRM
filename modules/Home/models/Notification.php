<?php

/**
 * Notification Model Class
 * @package YetiForce.Model
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Home_Notification_Model extends Vtiger_Base_Model
{

	/**
	 * Function to get the instance
	 * @return <Home_Notification_Model>
	 */
	public static function getInstance()
	{
		return new self();
	}

	public function getTypes()
	{
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$role = str_replace('H', '', $currentUser->get('roleid'));
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT * FROM a_yf_notification_type WHERE role = ? OR role = ?', [0, $role]);
		$types = [];
		while ($row = $db->getRow($result)) {
			$types[$row['id']] = $row;
		}
		return $types;
	}

	public function getEntries()
	{
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$sql = 'SELECT * FROM l_yf_notification WHERE userid = ?'; //ORDER BY id DESC
		$result = $db->pquery($sql, [$currentUser->getId()]);
		$entries = [];
		while ($row = $db->getRow($result)) {
			$entries[$row['type']][] = Home_NoticeEntries_Model::getInstanceByRow($row);
		}
		return $entries;
	}

	public function getNumberOfEntries()
	{
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$result = $db->pquery('SELECT count(*) FROM l_yf_notification WHERE userid = ?', [$currentUser->getId()]);
		return $db->getSingleValue($result);
	}

	public function save($parseContent = true)
	{
		$log = LoggerManager::getInstance();
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '| ');

		$currentUser = vglobal('current_user');
		$user = CRMEntity::getInstance('Users');
		$user->retrieveCurrentUserInfoFromFile($this->get('userid'));
		vglobal('current_user', $user);
		if (!Users_Privileges_Model::isPermitted($this->get('moduleName'), 'DetailView', $this->get('record'))) {
			$log->error('User ' . Vtiger_Functions::getOwnerRecordLabel($this->get('userid')) .
				' does not have permission for this record ' . $this->get('record'));
			vglobal('current_user', $currentUser);
			return false;
		}
		if ($parseContent) {
			$this->parseContent();
		}
		vglobal('current_user', $currentUser);

		if (!$this->has('time')) {
			$this->set('time', date('Y-m-d H:i:s'));
		}
		$db = PearDatabase::getInstance();
		$db->insert('l_yf_notification', [
			'userid' => $this->get('userid'),
			'type' => $this->get('type'),
			'message' => $this->get('message'),
			'reletedid' => $this->get('record'),
			'time' => $this->get('time')
		]);

		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' - return true');
		return true;
	}

	public function parseContent()
	{
		$message = Vtiger_Cache::get('NotificationParseContent', $this->get('message') . $this->get('record'));
		if ($message !== false) {
			$this->set('message', $message);
			return $message;
		}
		$message = $this->get('message');

		$textParser = Vtiger_TextParser_Helper::getInstanceById($this->get('record'), $this->get('moduleName'));
		$textParser->set('withoutTranslations', true);
		$textParser->setContent($message);
		$message = $textParser->parse();

		$this->set('message', $message);
		Vtiger_Cache::set('NotificationParseContent', $this->get('message') . $this->get('record'), $message);
	}
}
