<?

class websiteEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->website = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->websiteId = makeIntString($this->ses['request']['param']['websiteId']);

	$this->website['title'] = $this->ses['request']['param']['com_title'];
	#$this->website['description'] = $this->ses['request']['param']['com_description'];
	$this->website['relation-self'] = $this->ses['request']['param']['com_relation-self'];

	//
	// check fields?
	//
	$input = array($this->website['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->website['relation-self'] = urlCompletion($this->website['relation-self']);

	// allow update?
	if (! array_key_exists($this->websiteId, ScioMinoApiGetWebsite($this->userId, $this->websiteId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$websiteId = ScioMinoApiUpdateWebsite($this->website, $this->userId, $this->websiteId);

		if ($websiteId != 0) {
			$this->status = "De website is bewerkt.";
		}
		else{
			$this->status = "De website kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['websiteId'] = $websiteId;
		$this->ses['response']['param']['websiteTitle'] = $this->website['title'];
		$this->ses['response']['param']['websiteRelation-self'] = $this->website['relation-self'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/websiteEditForm2.php';
        }
        
	// show the form
        else {
		$this->website = ScioMinoApiGetWebsite($this->userId, $this->websiteId);
		$this->ses['response']['param']['website'] = $this->website[$this->websiteId];
		$this->ses['response']['param']['websiteId'] = $this->websiteId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
