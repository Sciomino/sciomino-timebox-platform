<?

class focusDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->focusId = makeIntString($this->ses['request']['param']['focusId']);

	// allow delete?
	$query = "name=focus&userId=".$this->userId;
	$this->focus = UserApiListDataWithQuery($query);
	if (array_key_exists($this->focusId, $this->focus)) {
		// delete 
		if (UserApiDeleteData($this->focusId) != 0) {
			$this->status = "De focus is verwijderd.";
		}
		else{
			$this->status = "De focus kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
