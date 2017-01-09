<?

class otherPubNew extends control {

    function Run() {

        global $XCOW_B;

	$otherPubId = 0;
	$this->otherPub = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->otherPub['title'] = $this->ses['request']['param']['com_title'];
	$this->otherPub['alternative'] = $this->ses['request']['param']['alternative'];
	$this->otherPub['description'] = $this->ses['request']['param']['description'];
	$this->otherPub['relation-self'] = $this->ses['request']['param']['relation-self'];
	$this->fill = $this->ses['request']['param']['fill'];

	//
	// check fields?
	//
	$input = array($this->otherPub['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->otherPub['relation-self'] = urlCompletion($this->otherPub['relation-self']);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$otherPubId = ScioMinoApiSaveOtherPub ($this->otherPub, $this->userId, '1'); 

		if ($otherPubId != 0) {
			$this->status = "De overige publicatie is toegevoegd.";
		}
		else{
			$this->status = "De overige publicatie kon niet toegevoegd worden.";
		}

		$this->ses['response']['param']['otherPubId'] = $otherPubId;
		$this->ses['response']['param']['otherPubTitle'] = $this->otherPub['title'];
		$this->ses['response']['param']['otherPubAlternative'] = $this->otherPub['alternative'];
		$this->ses['response']['param']['otherPubDescription'] = $this->otherPub['description'];
		$this->ses['response']['param']['otherPubRelation-self'] = $this->otherPub['relation-self'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/otherPubNewForm2.php';
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
