<?

class websiteNew extends control {

    function Run() {

        global $XCOW_B;

	$websiteId = 0;
	$this->website = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->website['title'] = $this->ses['request']['param']['com_title'];
	#$this->website['description'] = $this->ses['request']['param']['com_description'];
	$this->website['relation-self'] = $this->ses['request']['param']['com_relation-self'];
	$this->fill = $this->ses['request']['param']['fill'];

	//
	// check fields?
	//
	$input = array($this->website['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->website['relation-self'] = urlCompletion($this->website['relation-self']);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$websiteId = ScioMinoApiSaveWebsite ($this->website, $this->userId, '1'); 

		if ($websiteId != 0) {
			$this->status = "De website is toegevoegd.";
		}
		else{
			$this->status = "De website kon niet toegevoegd worden.";
		}

		$this->ses['response']['param']['websiteId'] = $websiteId;
		$this->ses['response']['param']['websiteTitle'] = $this->website['title'];
		$this->ses['response']['param']['websiteRelation-self'] = $this->website['relation-self'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/websiteNewForm2.php';
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
