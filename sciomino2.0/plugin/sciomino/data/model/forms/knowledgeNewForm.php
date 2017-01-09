<?

class knowledgeNew extends control {

    function Run() {

		global $XCOW_B;

		$knowledgeId = 0;
		$this->knowledge = array();

		// who?
		$this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id);

		// params
		$this->knowledge['field'] = makeString($this->ses['request']['param']['com_field'], 32);
		$this->knowledge['level'] = $this->ses['request']['param']['com_level'];
		$this->fill = $this->ses['request']['param']['fill'];
		// mode=ikook to display another view afer success
		// mode=modal to disable reset button on load
		$this->mode = $this->ses['request']['param']['mode'];

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
			if (ucfirst(strtolower($val['field'])) == $this->knowledge['field']) {
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
			$knowledgeId = ScioMinoApiSaveKnowledge($this->knowledge, $this->userId, '1');
			if ($knowledgeId != 0) {
				$this->status = "Het kennisveld is toegevoegd.";
			}
			else{
				$this->status = "Het kennisveld kon niet toegevoegd worden.";
			}
			$this->ses['response']['param']['knowledgeId'] = $knowledgeId;
			$this->ses['response']['param']['knowledgeField'] = $this->knowledge['field'];
			$this->ses['response']['param']['knowledgeLevel'] = $this->knowledge['level'];

			if ($this->mode == "ikook") {
				$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/knowledgeNewFormIkOok2.php';
			}
			else {
				$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/knowledgeNewForm2.php';
			}
        }
        
		// show the form
        else {
			$this->ses['response']['param']['fill'] = $this->fill;
			$this->ses['response']['param']['mode'] = $this->mode;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
