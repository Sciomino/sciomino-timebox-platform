<?

class shareDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->shareId = makeIntString($this->ses['request']['param']['shareId']);

	// allow delete?
	$this->share = ScioMinoApiGetShare($this->userId, $this->shareId);
	if (array_key_exists($this->shareId, $this->share)) {
		// delete 
		if (ScioMinoApiDeleteShare($this->userId, $this->shareId) != 0) {
			$this->status = "De presentatie site is verwijderd.";
		}
		else{
			$this->status = "De presentatie site kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
