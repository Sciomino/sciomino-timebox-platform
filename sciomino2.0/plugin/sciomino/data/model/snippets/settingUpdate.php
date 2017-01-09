<?

class settingUpdate extends control {

    function Run() {

        global $XCOW_B;

		$updateId = 0;

		// who?
		$this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id);


		// params
		$this->user = makeIntString($this->ses['request']['param']['user']);
		$this->dataId = makeIntString($this->ses['request']['param']['id']);
		$this->name = $this->ses['request']['param']['name'];
		$this->value = $this->ses['request']['param']['value'];

		//
		// check fields?
		//
		if ($this->userId != $this->user) {
			$this->status = "access denied";
		}

		if (count(UserApiListDataById($this->dataId)) == 0) {
			$this->status = "data does not exist";
		}

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

			// update
			$dataEntry = array();
			$dataEntry['name'] = $this->name;
			$dataEntry['value'] = $this->value;
			$updateId = UserApiUpdateData($this->dataId, $dataEntry);

			if ($updateId != 0) {
				$this->status = "Data entry is gewijzigd.";
			}
			else{
				$this->status = "Data entry kon niet gewijzigd worden.";
			}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
