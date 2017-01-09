<?

class websiteDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->websiteId = makeIntString($this->ses['request']['param']['websiteId']);

	// allow delete?
	$this->website = ScioMinoApiGetWebsite($this->userId, $this->websiteId);
	if (array_key_exists($this->websiteId, $this->website)) {
		// delete 
		if (ScioMinoApiDeleteWebsite($this->userId, $this->websiteId) != 0) {
			$this->status = "De website is verwijderd.";
		}
		else{
			$this->status = "De website kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
