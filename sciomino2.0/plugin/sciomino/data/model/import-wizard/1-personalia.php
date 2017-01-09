<?

class wizardPersonalia extends control {

    function Run() {

        global $XCOW_B;

        $this->id = $this->ses['id'];
        $status = NULL;

		$this->go = $this->ses['request']['param']['go'];

		// local additions
		$this->userInfo = array();
		$this->annotation = array();
		$this->userInfo['firstName'] = $this->ses['request']['param']['firstName'];
		$this->userInfo['lastName'] = $this->ses['request']['param']['lastName'];
		$this->annotation['dateofbirthday'] = $this->ses['request']['param']['dateofbirthday'];
		$this->annotation['dateofbirthmonth'] = $this->ses['request']['param']['dateofbirthmonth'];
		$this->annotation['dateofbirthyear'] = $this->ses['request']['param']['dateofbirthyear'];
		$this->annotation['gender'] = $this->ses['request']['param']['gender'];

		//
		// check fields?
		//
		$input = array($this->userInfo['firstName'], $this->userInfo['lastName'], $this->annotation['dateofbirthday'], $this->annotation['dateofbirthmonth'], $this->annotation['dateofbirthyear'], $this->annotation['gender']);
		if (! noEmptyInput($input) ) {
			$status = language("session_status_register_requiredfield");
		}
	
        //
        // if the fields are checked, update the user info
        // otherwise proceed to the view and show a form where new info can be entered
        //
        if ( ($this->go == 1) && (! isset($status))) {

		    updateDisplayName($this->id, $this->userInfo['firstName']." ".$this->userInfo['lastName']);
			if (($this->userId = UserApiUpdateUserByReference($this->userInfo, $this->id)) != 0) {
				$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);
		    }
		    else {
			// something terribly wrong, now what?
		    }

		    // $this->ses['response']['param']['status'] = $status;
	    	// $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/import-wizard/next.php';
	    	$this->ses['response']['redirect'] = $XCOW_B['url']."/wizard/step2";

        }
	    // registration attempt failed, try again
	    else {
			$this->ses['response']['param']['prevFirstName'] = $this->userInfo['firstName'];
			$this->ses['response']['param']['prevLastName'] = $this->userInfo['lastName'];
			$this->ses['response']['param']['prevDateofbirthday'] = $this->annotation['dateofbirthday'];
			$this->ses['response']['param']['prevDateofbirthmonth'] = $this->annotation['dateofbirthmonth'];
			$this->ses['response']['param']['prevDateofbirthyear'] = $this->annotation['dateofbirthyear'];
			$this->ses['response']['param']['prevGender'] = $this->annotation['gender'];

			if ($this->go == 1 ) {
				$this->ses['response']['param']['status'] = $status;
			}
			else {
				$this->ses['response']['param']['status'] = "";
			}
		
        }

	}

}

?>
