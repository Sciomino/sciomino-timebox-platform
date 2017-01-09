<?

class focusNew extends control {

    function Run() {

        global $XCOW_B;

	$insertId = 0;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	$this->focus = $this->ses['request']['query_string'];

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

		// save
		$data = array();
		$data['name'] = 'focus';
		$data['value'] = $this->focus;
		$data['userId'] = $this->userId;

		$insertId = UserApiSaveData($data);
		if ($insertId != 0) {
			$this->status = "Ok.";
		}
		else{
			$this->status = "Niet Ok.";
		}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
