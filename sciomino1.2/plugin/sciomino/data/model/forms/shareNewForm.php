<?

class shareNew extends control {

    function Run() {

        global $XCOW_B;

	$shareId = 0;
	$this->share = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->share['title'] = $this->ses['request']['param']['com_title'];
	#$this->share['description'] = $this->ses['request']['param']['com_description'];
	$this->share['relation-self'] = $this->ses['request']['param']['com_relation-self'];
	$this->fill = $this->ses['request']['param']['fill'];

	//
	// check fields?
	//
	$input = array($this->share['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	#no completion voor slideshare
	#$this->share['relation-self'] = urlCompletion($this->share['relation-self']);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$shareId = ScioMinoApiSaveShare ($this->share, $this->userId, '1'); 

		if ($shareId != 0) {
			$this->status = "De presentatie site is toegevoegd.";
		}
		else{
			$this->status = "De presentatie site kon niet toegevoegd worden.";
		}

		$this->ses['response']['param']['shareId'] = $shareId;
		$this->ses['response']['param']['shareTitle'] = $this->share['title'];
		$this->ses['response']['param']['shareRelation-self'] = $this->share['relation-self'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/shareNewForm2.php';
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
