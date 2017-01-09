<?

class otherPubDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->otherPubId = makeIntString($this->ses['request']['param']['otherPubId']);

	// allow delete?
	$this->otherPub = ScioMinoApiGetOtherPub($this->userId, $this->otherPubId);
	if (array_key_exists($this->otherPubId, $this->otherPub)) {
		// delete 
		if (ScioMinoApiDeleteOtherPub($this->userId, $this->otherPubId) != 0) {
			$this->status = "De overige publicatie is verwijderd.";
		}
		else{
			$this->status = "De overige publicatie kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
