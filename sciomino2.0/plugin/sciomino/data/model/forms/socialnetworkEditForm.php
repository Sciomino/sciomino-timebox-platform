<?

class socialnetworkEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->network = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->networkId = $this->ses['request']['param']['networkId'];

	$this->network['title'] = $this->ses['request']['param']['com_title'];
	$this->network['description'] = $this->ses['request']['param']['com_description'];
	$this->network['relation-self'] = $this->ses['request']['param']['com_relation-self'];

	//
	// check fields?
	//
	$input = array($this->network['title'], $this->network['description'], $this->network['relation-self']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	// allow update?
	if (! array_key_exists($this->networkId, ScioMinoApiGetSocialNetwork($this->userId, $this->networkId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$networkId = ScioMinoApiUpdateSocialNetwork($this->network, $this->userId, $this->networkId);

		if ($networkId != 0) {
			$this->status = "Het sociale netwerk is bewerkt.";
		}
		else{
			$this->status = "Het sociale netwerk kon niet bewerkt worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/socialnetworkEditForm2.php';
        }
        
	// show the form
        else {
		$this->network = ScioMinoApiGetSocialNetwork($this->userId, $this->networkId);
		$this->ses['response']['param']['network'] = $this->network[$this->networkId];
		$this->ses['response']['param']['networkId'] = $this->networkId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
