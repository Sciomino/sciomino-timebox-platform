<?

class knowledgeEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->knowledge = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->knowledgeId = makeIntString($this->ses['request']['param']['knowledgeId']);

	$this->knowledge['field'] = $this->ses['request']['param']['com_field'];
	$this->knowledge['level'] = $this->ses['request']['param']['com_level'];

	//
	// check fields?
	//
	$input = array($this->knowledge['field'], $this->knowledge['level']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	$this->knowledge['field'] = ucfirst(strtolower($this->knowledge['field']));
	$knowledgeList = ScioMinoApiListKnowledge($this->userId);
	foreach ($knowledgeList as $key => $val) {
		if (ucfirst(strtolower($val['field'])) == $this->knowledge['field'] && $val['level'] == $this->knowledge['level']) {
			$this->status = "Same Same";
			break;
		}
	}

	// allow update?
	if (! array_key_exists($this->knowledgeId, ScioMinoApiGetKnowledge($this->userId, $this->knowledgeId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$knowledgeId = ScioMinoApiUpdateKnowledge($this->knowledge, $this->userId, $this->knowledgeId);
		if ($knowledgeId != 0) {
			$this->status = "Het kennisveld is bewerkt.";
		}
		else{
			$this->status = "Het kennisveld kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['knowledgeId'] = $knowledgeId;
		$this->ses['response']['param']['knowledgeField'] = $this->knowledge['field'];
		$this->ses['response']['param']['knowledgeLevel'] = $this->knowledge['level'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/knowledgeEditForm2.php';
        }
        
	// show the form
        else {
		$this->knowledge = ScioMinoApiGetKnowledge($this->userId, $this->knowledgeId);
		$this->ses['response']['param']['knowledge'] = $this->knowledge[$this->knowledgeId];
		$this->ses['response']['param']['knowledgeId'] = $this->knowledgeId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
