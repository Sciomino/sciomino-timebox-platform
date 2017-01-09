<?

class educationEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->education = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->educationId = makeIntString($this->ses['request']['param']['educationId']);

	$this->education['subject'] = $this->ses['request']['param']['com_subject'];
	$this->education['title'] = $this->ses['request']['param']['com_title'];
	$this->education['description'] = $this->ses['request']['param']['description'];
	$this->education['publisher'] = $this->ses['request']['param']['publisher'];
	$this->education['date'] = $this->ses['request']['param']['date'];
	$this->education['relation-self'] = $this->ses['request']['param']['relation-self'];
	$this->education['like'] = $this->ses['request']['param']['com_like'];

	//
	// check fields?
	//
	$input = array($this->education['subject'], $this->education['title'], $this->education['like'], $this->education['publisher']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->education['relation-self'] = urlCompletion($this->education['relation-self']);

	// allow update?
	if (! array_key_exists($this->educationId, ScioMinoApiGetEducation($this->userId, $this->educationId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$educationId = ScioMinoApiUpdateEducation($this->education, $this->userId, $this->educationId);

		if ($educationId != 0) {
			$this->status = "De opleidingservaring is bewerkt.";
		}
		else{
			$this->status = "De opleidingservaring kon niet bewerkt worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/educationEditForm2.php';
        }
        
	// show the form
        else {
		$this->education = ScioMinoApiGetEducation($this->userId, $this->educationId);
		$this->ses['response']['param']['education'] = $this->education[$this->educationId];
		$this->ses['response']['param']['educationId'] = $this->educationId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
