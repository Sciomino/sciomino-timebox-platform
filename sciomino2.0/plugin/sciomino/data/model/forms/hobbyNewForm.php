<?

class hobbyNew extends control {

    function Run() {

        global $XCOW_B;

	$hobbyId = 0;
	$this->hobby = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->hobby['field'] = $this->ses['request']['param']['com_field'];
	$this->fill = $this->ses['request']['param']['fill'];
	$this->mode = $this->ses['request']['param']['mode'];

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

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$hobbyId = ScioMinoApiSaveHobby($this->hobby, $this->userId, '1');
		if ($hobbyId != 0) {
			$this->status = "De hobby is toegevoegd.";
		}
		else{
			$this->status = "De hobby kon niet toegevoegd worden.";
		}

		$this->ses['response']['param']['hobbyId'] = $hobbyId;
		$this->ses['response']['param']['hobbyField'] = $this->hobby['field'];

 		if ($this->mode == "ikook") {
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/hobbyNewFormIkOok2.php';
		}
		else {
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/hobbyNewForm2.php';
		}
        }
        
	// show the form
        else {
		#$this->ses['response']['param']['productId'] = $this->productId;
		$this->ses['response']['param']['fill'] = $this->fill;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
