<?

class actMailblockDelete extends control {

    function Run() {

        global $XCOW_B;

		$mailblockId = 0;
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
		if (count($actList[$this->act]['Mailblock']) > 0) {
			$mailblockId = get_id_from_multi_array($actList[$this->act]['Mailblock'], 'Reference', $this->id);
			if ($mailblockId != 0) {
				$this->status = NULL; 
			}
		}

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

			// delete
			$deleteId = AnswersApiDeleteActMailblock($mailblockId);
			if ($deleteId != 0) {
				$this->status = "De mailblock is verwijderd.";
			}
			else{
				$this->status = "De mailblock kon niet verwijderd worden.";
			}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;
        $this->ses['response']['param']['act'] = $this->act;

    }

}

?>
