<?

class educationNew extends control {

    function Run() {

        global $XCOW_B;

	$this->educationId = 0;
	$this->education = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->education['subject'] = $this->ses['request']['param']['com_subject'];
	$this->education['title'] = $this->ses['request']['param']['com_title'];
	$this->education['description'] = $this->ses['request']['param']['description'];
	$this->education['publisher'] = $this->ses['request']['param']['publisher'];
	$this->education['date'] = $this->ses['request']['param']['date'];
	$this->education['relation-self'] = $this->ses['request']['param']['relation-self'];
	$this->education['like'] = $this->ses['request']['param']['com_like'];

	$this->fillSubject = $this->ses['request']['param']['fillSubject'];
	$this->fillTitle = $this->ses['request']['param']['fillTitle'];
	$this->fillPublisher = $this->ses['request']['param']['fillPublisher'];

	$this->go = $this->ses['request']['param']['go'];
	if (! isset($this->go)) { $this->go = 0; }

	//
	// check fields?
	//
	$missingFields = 0;
	$input = array($this->education['subject'], $this->education['title'], $this->education['like'], $this->education['publisher']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
		$missingFields = 1;
	}
	$this->education['relation-self'] = urlCompletion($this->education['relation-self']);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$this->educationId = ScioMinoApiSaveEducation ($this->education, $this->userId, '1'); 

		if ($this->educationId != 0) {
			$this->status = "De opleidingservaring is toegevoegd.";
		}
		else{
			$this->status = "De opleidingservaring kon niet toegevoegd worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/educationNewForm2.php';
        }
        
	// show the form
        else {
			#$this->ses['response']['param']['productId'] = $this->productId;
			$this->ses['response']['param']['prevSubject'] = $this->education['subject'];
			$this->ses['response']['param']['prevTitle'] = $this->education['title'];
			$this->ses['response']['param']['prevPublisher'] = $this->education['publisher'];
			$this->ses['response']['param']['fillSubject'] = $this->fillSubject;
			$this->ses['response']['param']['fillTitle'] = $this->fillTitle;
			$this->ses['response']['param']['fillPublisher'] = $this->fillPublisher;
			$this->ses['response']['param']['go'] = $this->go;
 			$this->ses['response']['param']['missing'] = $missingFields;
       }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
