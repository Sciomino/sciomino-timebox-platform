<?

class eventEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->event = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->eventId = makeIntString($this->ses['request']['param']['eventId']);

	$this->event['subject'] = $this->ses['request']['param']['com_subject'];
	$this->event['title'] = $this->ses['request']['param']['com_title'];
	$this->event['description'] = $this->ses['request']['param']['description'];
	$this->event['publisher'] = $this->ses['request']['param']['publisher'];
	$this->event['date'] = $this->ses['request']['param']['date'];
	$this->event['relation-self'] = $this->ses['request']['param']['relation-self'];
	$this->event['like'] = $this->ses['request']['param']['com_like'];

	//
	// check fields?
	//
	$input = array($this->event['subject'], $this->event['title'], $this->event['like'], $this->event['publisher']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->event['relation-self'] = urlCompletion($this->event['relation-self']);

	// allow update?
	if (! array_key_exists($this->eventId, ScioMinoApiGetEvent($this->userId, $this->eventId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$eventId = ScioMinoApiUpdateEvent($this->event, $this->userId, $this->eventId);

		if ($eventId != 0) {
			$this->status = "De evenementervaring is bewerkt.";
		}
		else{
			$this->status = "De evenementervaring kon niet bewerkt worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/eventEditForm2.php';
        }
        
	// show the form
        else {
		$this->event = ScioMinoApiGetEvent($this->userId, $this->eventId);
		$this->ses['response']['param']['event'] = $this->event[$this->eventId];
		$this->ses['response']['param']['eventId'] = $this->eventId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
