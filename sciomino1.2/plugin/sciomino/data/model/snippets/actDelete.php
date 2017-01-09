<?

class actDelete extends control {

    function Run() {

        global $XCOW_B;

	$deleteId = 0;

	// who?
        $this->id = $this->ses['id'];

	// params
	$this->act = makeIntString($this->ses['request']['param']['act']);
	$this->parent = makeIntString($this->ses['request']['param']['parent']);

	//
	// check fields?
	//
	$this->status = "access denied"; 
	if (!isset($this->parent)) {
		$actList = AnswersApiListActById($this->act);
	}
	else {
		$actString = "act[".$this->act."]&parent=".$this->parent;
		$actList = AnswersApiListActWithQuery($actString);

	}
	if ($actList[$this->act]['Reference'] == $this->id) {
		$this->status = NULL; 
	}

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {
		// delete
		$deleteId = AnswersApiDeleteAct($this->act);
		if ($deleteId != 0) {
			$this->status = "De act is verwijderd.";
		}
		else {
			$this->status = "De act kon niet verwijderd worden.";
		}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
