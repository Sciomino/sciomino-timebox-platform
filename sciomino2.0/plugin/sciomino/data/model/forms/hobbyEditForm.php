<?

class hobbyEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->hobby = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->hobbyId = makeIntString($this->ses['request']['param']['hobbyId']);

	$this->hobby['field'] = $this->ses['request']['param']['com_field'];

	//
	// check fields?
	//
	$input = array($this->hobby['field']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	$this->hobby['field'] = ucfirst(strtolower($this->hobby['field']));
	$hobbyList = ScioMinoApiListHobby($this->userId);
	foreach ($hobbyList as $key => $val) {
		if (ucfirst(strtolower($val['field'])) == $this->hobby['field'] ) {
			$this->status = "Same Same";
			break;
		}
	}

	// allow update?
	if (! array_key_exists($this->hobbyId, ScioMinoApiGetHobby($this->userId, $this->hobbyId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$hobbyId = ScioMinoApiUpdateHobby($this->hobby, $this->userId, $this->hobbyId);
		if ($hobbyId != 0) {
			$this->status = "De hobby is bewerkt.";
		}
		else{
			$this->status = "De hobby kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['hobbyId'] = $hobbyId;
		$this->ses['response']['param']['hobbyField'] = $this->hobby['field'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/hobbyEditForm2.php';
        }
        
	// show the form
        else {
		$this->hobby = ScioMinoApiGetHobby($this->userId, $this->hobbyId);
		$this->ses['response']['param']['hobby'] = $this->hobby[$this->hobbyId];
		$this->ses['response']['param']['hobbyId'] = $this->hobbyId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
