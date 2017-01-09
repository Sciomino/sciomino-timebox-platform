<?

class shareEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->share = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->shareId = makeIntString($this->ses['request']['param']['shareId']);

	$this->share['title'] = $this->ses['request']['param']['com_title'];
	#$this->share['description'] = $this->ses['request']['param']['com_description'];
	$this->share['relation-self'] = $this->ses['request']['param']['com_relation-self'];

	//
	// check fields?
	//
	$input = array($this->share['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	#no completion voor slideshare
	#$this->share['relation-self'] = urlCompletion($this->share['relation-self']);

	// allow update?
	if (! array_key_exists($this->shareId, ScioMinoApiGetShare($this->userId, $this->shareId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$shareId = ScioMinoApiUpdateShare($this->share, $this->userId, $this->shareId);

		if ($shareId != 0) {
			$this->status = "De presentatie site is bewerkt.";
		}
		else{
			$this->status = "De presentatie site kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['shareId'] = $shareId;
		$this->ses['response']['param']['shareTitle'] = $this->share['title'];
		$this->ses['response']['param']['shareRelation-self'] = $this->share['relation-self'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/shareEditForm2.php';
        }
        
	// show the form
        else {
		$this->share = ScioMinoApiGetShare($this->userId, $this->shareId);
		$this->ses['response']['param']['share'] = $this->share[$this->shareId];
		$this->ses['response']['param']['shareId'] = $this->shareId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
