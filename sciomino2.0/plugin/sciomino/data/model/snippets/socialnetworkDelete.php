<?

class socialnetworkDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->networkId = makeIntString($this->ses['request']['param']['networkId']);

	// allow delete?
	$this->network = ScioMinoApiGetSocialNetwork($this->userId, $this->networkId);
	if (array_key_exists($this->networkId, $this->network)) {
		// delete 
		if (ScioMinoApiDeleteSocialNetwork($this->userId, $this->networkId) != 0) {
			$this->status = "Het sociale netwerk is verwijderd.";
		}
		else{
			$this->status = "Het sociale netwerk kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
