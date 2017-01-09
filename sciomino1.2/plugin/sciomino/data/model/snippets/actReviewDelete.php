<?

class actReviewDelete extends control {

    function Run() {

        global $XCOW_B;

	$reviewId = 0;
	$deleteId = 0;

	// who?
        $this->id = $this->ses['id'];

	// params
	$this->act = makeIntString($this->ses['request']['param']['act']);

	//
	// check fields?
	//
	$this->status = "access denied"; 
	$actList = AnswersApiListActById($this->act);
	if (count($actList[$this->act]['Review']) > 0) {
		$reviewId = get_id_from_multi_array($actList[$this->act]['Review'], 'Reference', $this->id);
		if ($reviewId != 0) {
			$this->status = NULL; 
		}
	}

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

		// delete
		$deleteId = AnswersApiDeleteActReview($reviewId);
		if ($deleteId != 0) {
			$this->status = "De review is verwijderd.";
		}
		else{
			$this->status = "De review kon niet verwijderd worden.";
		}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;
        $this->ses['response']['param']['act'] = $this->act;

    }

}

?>
