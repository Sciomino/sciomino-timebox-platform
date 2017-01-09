<?

class eventNew extends control {

    function Run() {

        global $XCOW_B;

	$this->eventId = 0;
	$this->event = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->event['subject'] = $this->ses['request']['param']['com_subject'];
	$this->event['title'] = $this->ses['request']['param']['com_title'];
	$this->event['description'] = $this->ses['request']['param']['description'];
	$this->event['publisher'] = $this->ses['request']['param']['publisher'];
	$this->event['date'] = $this->ses['request']['param']['date'];
	$this->event['relation-self'] = $this->ses['request']['param']['relation-self'];
	$this->event['like'] = $this->ses['request']['param']['com_like'];

	$this->fillSubject = $this->ses['request']['param']['fillSubject'];
	$this->fillTitle = $this->ses['request']['param']['fillTitle'];
	$this->fillPublisher = $this->ses['request']['param']['fillPublisher'];

	$this->go = $this->ses['request']['param']['go'];
	if (! isset($this->go)) { $this->go = 0; }

	//
	// check fields?
	//
	$missingFields = 0;
	$input = array($this->event['subject'], $this->event['title'], $this->event['like'], $this->event['publisher']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
		$missingFields = 1;
	}
	$this->event['relation-self'] = urlCompletion($this->event['relation-self']);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$this->eventId = ScioMinoApiSaveEvent ($this->event, $this->userId, '1'); 

		if ($this->eventId != 0) {
			$this->status = "De evenementervaring is toegevoegd.";
		}
		else{
			$this->status = "De evenementervaring kon niet toegevoegd worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/eventNewForm2.php';
        }
        
	// show the form
        else {
			#$this->ses['response']['param']['productId'] = $this->productId;
			$this->ses['response']['param']['prevSubject'] = $this->event['subject'];
			$this->ses['response']['param']['prevTitle'] = $this->event['title'];
			$this->ses['response']['param']['prevPublisher'] = $this->event['publisher'];
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
