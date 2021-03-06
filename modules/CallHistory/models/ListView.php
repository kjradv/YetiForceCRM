<?php
/* +***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 * *********************************************************************************************************************************** */

class CallHistory_ListView_Model extends Vtiger_ListView_Model
{

	/**
	 * Overrided to remove add button 
	 */
	public function getBasicLinks()
	{
		$basicLinks = [];
		if (Users_Privileges_Model::isPermitted($moduleModel->getName(), 'WatchingModule')) {
			$watchdog = Vtiger_Watchdog_Model::getInstance($moduleModel->getName());
			$class = 'btn-default';
			if ($watchdog->isWatchingModule()) {
				$class = 'btn-info';
			}
			$basicLinks[] = [
				'linktype' => 'LISTVIEWBASIC',
				'linkhint' => 'BTN_WATCHING_MODULE',
				'linkurl' => 'javascript:Vtiger_List_Js.changeWatchingModule(this)',
				'linkclass' => $class,
				'linkicon' => 'glyphicon glyphicon-eye-open',
				'linkdata' => ['off' => 'btn-default', 'on' => 'btn-info', 'value' => $watchdog->isWatchingModule() ? 0 : 1],
			];
		}
		return $basicLinks;
	}

	/**
	 * Overrided to remove Mass Edit Option 
	 */
	public function getListViewMassActions($linkParams)
	{
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$moduleModel = $this->getModule();

		$linkTypes = array('LISTVIEWMASSACTION');
		$links = Vtiger_Link_Model::getAllByType($moduleModel->getId(), $linkTypes, $linkParams);

		return $links;
	}
}
