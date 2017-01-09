<?

class notificationList extends control {

    function Run() {

    global $XCOW_B;
        
	$userData = array();
	$notificationList = array();

	// who?
    $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	# get data list
	$userData = UserApiListDataWithQuery("userId=".$this->userId);

	# init data entries if not exists
	if (get_id_from_multi_array($userData, 'Name', 'mailKnowledgePreference') == 0) {
		$dataEntry = array();
		$dataEntry['userId'] = $this->userId;
		$dataEntry['name'] = "mailKnowledgePreference";
		$dataEntry['value'] = $XCOW_B['sciomino']['skin-notify'];
		UserApiSaveData($dataEntry);
		$userData = UserApiListDataWithQuery("userId=".$this->userId);
	}

	# get notification data
	foreach ($userData as $dataId => $dataList) {
		if ($dataList['Name'] == "mailKnowledgePreference") {
			$notificationList[$dataList['Id']] = array();
			$notificationList[$dataList['Id']]['Id'] = $dataList['Id'];
			$notificationList[$dataList['Id']]['Name'] = $dataList['Name'];
			$notificationList[$dataList['Id']]['Value'] = $dataList['Value'];
			$notificationList[$dataList['Id']]['Checked'] = '';
			if ($dataList['Value'] == 1) {
				$notificationList[$dataList['Id']]['Checked'] = 'checked';
			}
		}
	}

	$this->ses['response']['param']['user'] = $this->userId;
	$this->ses['response']['param']['notificationList'] = $notificationList;

    }

}

?>
