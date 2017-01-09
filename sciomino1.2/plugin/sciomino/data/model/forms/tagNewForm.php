<?

class tagNew extends control {

    function Run() {

        global $XCOW_B;

	$tagId = 0;
	$this->tag = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->tag['name'] = $this->ses['request']['param']['com_name'];
	$this->fill = $this->ses['request']['param']['fill'];
	$this->mode = $this->ses['request']['param']['mode'];

	//
	// check fields?
	//
	$input = array($this->tag['name']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	$this->tag['name'] = tagCompletion($this->tag['name']);
	$tagList = ScioMinoApiListTag($this->userId);
	foreach ($tagList as $key => $val) {
		if ( $val['name'] == $this->tag['name'] ) {
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
		$tagId = ScioMinoApiSaveTag($this->tag, $this->userId, '1');
		if ($tagId != 0) {
			$this->status = "De #tag is toegevoegd.";
		}
		else{
			$this->status = "De #tag kon niet toegevoegd worden.";
		}

		$this->ses['response']['param']['tagId'] = $tagId;
		$this->ses['response']['param']['tagName'] = $this->tag['name'];

 		if ($this->mode == "ikook") {
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/tagNewFormIkOok2.php';
		}
		else {
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/tagNewForm2.php';
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
