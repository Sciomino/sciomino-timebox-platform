<?

class companyEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->company = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->companyId = makeIntString($this->ses['request']['param']['companyId']);

	$this->company['subject'] = $this->ses['request']['param']['com_subject'];
	$this->company['title'] = $this->ses['request']['param']['com_title'];
	$this->company['description'] = $this->ses['request']['param']['description'];
	$this->company['date'] = $this->ses['request']['param']['date'];
	$this->company['like'] = $this->ses['request']['param']['com_like'];

	//
	// check fields?
	//
	$input = array($this->company['subject'], $this->company['title'], $this->company['like']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	// allow update?
	if (! array_key_exists($this->companyId, ScioMinoApiGetCompany($this->userId, $this->companyId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$companyId = ScioMinoApiUpdateCompany($this->company, $this->userId, $this->companyId);

		if ($companyId != 0) {
			$this->status = "De bedrijfservaring is bewerkt.";
		}
		else{
			$this->status = "De bedrijfservaring kon niet bewerkt worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/companyEditForm2.php';
        }
        
	// show the form
        else {
		$this->company = ScioMinoApiGetCompany($this->userId, $this->companyId);
		$this->ses['response']['param']['company'] = $this->company[$this->companyId];
		$this->ses['response']['param']['companyId'] = $this->companyId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
