<?

class tagEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->tag = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->tagId = makeIntString($this->ses['request']['param']['tagId']);

	$this->tag['name'] = $this->ses['request']['param']['com_name'];

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

	// allow update?
	if (! array_key_exists($this->tagId, ScioMinoApiGetTag($this->userId, $this->tagId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$tagId = ScioMinoApiUpdateTag($this->tag, $this->userId, $this->tagId);
		if ($tagId != 0) {
			$this->status = "De #tag is bewerkt.";
		}
		else{
			$this->status = "De #tag kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['tagId'] = $tagId;
		$this->ses['response']['param']['tagName'] = $this->tag['name'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/tagEditForm2.php';
        }
        
	// show the form
        else {
		$this->tag = ScioMinoApiGetTag($this->userId, $this->tagId);
		$this->ses['response']['param']['tag'] = $this->tag[$this->tagId];
		$this->ses['response']['param']['tagId'] = $this->tagId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
