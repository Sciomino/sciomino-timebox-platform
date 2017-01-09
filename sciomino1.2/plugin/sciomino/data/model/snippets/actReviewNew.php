<?

class actReviewNew extends control {

    function Run() {

        global $XCOW_B;

	$insertId = 0;

	// who?
        $this->id = $this->ses['id'];

	// params

	$this->act = makeIntString($this->ses['request']['param']['act']);

	//
	// check fields?
	//

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

		// save
		$insertId = AnswersApiSaveActReview ($this->act, $this->id, '1'); 
		if ($insertId != 0) {
			$this->status = "De review is toegevoegd.";
		}
		else{
			$this->status = "De review kon niet toegevoegd worden.";
		}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;
        $this->ses['response']['param']['act'] = $this->act;

    }

}

?>
