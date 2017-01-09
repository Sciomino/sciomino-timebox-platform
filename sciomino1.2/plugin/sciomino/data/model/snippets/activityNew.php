<?

class activityNew extends control {

    function Run() {

        global $XCOW_B;

		$insertId = 0;

		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id);

		// params
		$this->title = $this->ses['request']['param']['title'];
		$this->description = $this->ses['request']['param']['description'];

		# redirect = [URL]
		$this->redirect = $this->ses['request']['param']['redirect'];

		//
		// check fields?
		//
		$input = array($this->title, $this->description);
		if (! noEmptyInput($input) ) {
			$this->status = "Input Error";
		}
		
        //
        // if the fields are checked, go for it
        // otherwise nada
        //
		if (! $this->status) {

			// save
			$activity = array();
			$activity['userId'] = $this->userId;
			$activity['title'] = $this->title;
			$activity['description'] = $this->description;
			$activity['priority'] = 50;
			$activity['url'] = '';

			$insertId = UserApiSaveActivity($activity, "SC_UserApiListActivityWithQuery_");
			if ($insertId != 0) {
				$this->status = "Ok.";
			}
			else{
				$this->status = "Niet Ok.";
			}

        }       

        $this->ses['response']['param']['status'] = $this->status;

		// redirect
		if ($this->redirect != '') {
			$this->ses['response']['redirect'] = $this->redirect;
		}

    }

}

?>
