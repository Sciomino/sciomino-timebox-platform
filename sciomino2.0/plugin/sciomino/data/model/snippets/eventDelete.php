<?

class eventDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->eventId = makeIntString($this->ses['request']['param']['eventId']);

	// allow delete?
	$this->event = ScioMinoApiGetEvent($this->userId, $this->eventId);
	if (array_key_exists($this->eventId, $this->event)) {
		// delete 
		if (ScioMinoApiDeleteEvent($this->userId, $this->eventId) != 0) {
			$this->status = "De evenementervaring is verwijderd.";
		}
		else{
			$this->status = "De evenementervaring kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
