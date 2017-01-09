<?

class settingLanguage extends control {

    function Run() {

        global $XCOW_B;
        
		// who?
		$this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id);

		// check & set language
		$userData = UserApiListDataWithQuery("userId=".$this->userId);
		if (get_id_from_multi_array($userData, 'Name', 'mailLanguage') != 0) {
			$setLanguage = 0;
			$curId = 0;
			foreach ($userData as $dataId => $dataList) {
				if ($dataList['Name'] == "mailLanguage") {
					if ($dataList['Value'] != $this->ses['response']['language']) {
						$setLanguage = 1;
						$curId = $dataList['Id'];
					}
				}
			}
			if ($setLanguage == 1) {
				UserApiDeleteData($curId);
				$dataEntry = array();
				$dataEntry['userId'] = $this->userId;
				$dataEntry['name'] = "mailLanguage";
				$dataEntry['value'] = $this->ses['response']['language'];
				UserApiSaveData($dataEntry);
			}
		}
		else {
			$dataEntry = array();
			$dataEntry['userId'] = $this->userId;
			$dataEntry['name'] = "mailLanguage";
			$dataEntry['value'] = $this->ses['response']['language'];
			UserApiSaveData($dataEntry);
		}
		
		// param
		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
		$this->ses['response']['param']['view'] = $XCOW_B['sciomino']['shortcut-view'];

     }

}

?>
