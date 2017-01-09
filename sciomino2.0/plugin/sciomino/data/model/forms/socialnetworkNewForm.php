<?

class socialnetworkNew extends control {

    function Run() {

        global $XCOW_B;

	$this->networkId = 0;
	$this->network = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

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

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$this->networkId = ScioMinoApiSaveSocialNetwork ($this->network, $this->userId, '1'); 

		if ($this->networkId != 0) {
			$this->status = "Het sociale netwerk is toegevoegd.";
		}
		else{
			$this->status = "Het sociale netwerk kon niet toegevoegd worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/socialnetworkNewForm2.php';
        }
        
	// show the form
        else {
		#$this->ses['response']['param']['productId'] = $this->productId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
