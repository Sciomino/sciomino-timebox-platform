<?

class hobbyDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->hobbyId = makeIntString($this->ses['request']['param']['hobbyId']);

	// allow delete?
	$this->hobby = ScioMinoApiGetHobby($this->userId, $this->hobbyId);
	if (array_key_exists($this->hobbyId, $this->hobby)) {
		// delete 
		if (ScioMinoApiDeleteHobby($this->userId, $this->hobbyId) != 0) {
			$this->status = "De hobby is verwijderd.";
		}
		else{
			$this->status = "De hobby kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
