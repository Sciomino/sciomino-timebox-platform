<?

class companyNew extends control {

    function Run() {

        global $XCOW_B;

	$this->companyId = 0;
	$this->company = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->company['subject'] = $this->ses['request']['param']['com_subject'];
	$this->company['title'] = $this->ses['request']['param']['com_title'];
	$this->company['description'] = $this->ses['request']['param']['description'];
	$this->company['date'] = $this->ses['request']['param']['date'];
	$this->company['like'] = $this->ses['request']['param']['com_like'];

	$this->fillSubject = $this->ses['request']['param']['fillSubject'];
	$this->fillTitle = $this->ses['request']['param']['fillTitle'];

	$this->go = $this->ses['request']['param']['go'];
	if (! isset($this->go)) { $this->go = 0; }

	//
	// check fields?
	//
	$missingFields = 0;
	$input = array($this->company['subject'], $this->company['title'], $this->company['like']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
		$missingFields = 1;
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$this->companyId = ScioMinoApiSaveCompany ($this->company, $this->userId, '1'); 

		if ($this->companyId != 0) {
			$this->status = "De bedrijfservaring is toegevoegd.";
		}
		else{
			$this->status = "De bedrijfservaring kon niet toegevoegd worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/companyNewForm2.php';
        }
        
	// show the form
        else {
			#$this->ses['response']['param']['productId'] = $this->productId;
			$this->ses['response']['param']['prevSubject'] = $this->company['subject'];
			$this->ses['response']['param']['prevTitle'] = $this->company['title'];
			$this->ses['response']['param']['fillSubject'] = $this->fillSubject;
			$this->ses['response']['param']['fillTitle'] = $this->fillTitle;
			$this->ses['response']['param']['go'] = $this->go;
			$this->ses['response']['param']['missing'] = $missingFields;
		}

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
