<?

class tagDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->tagId = makeIntString($this->ses['request']['param']['tagId']);

	// allow delete?
	$this->tag = ScioMinoApiGetTag($this->userId, $this->tagId);
	if (array_key_exists($this->tagId, $this->tag)) {
		// delete 
		if (ScioMinoApiDeleteTag($this->userId, $this->tagId) != 0) {
			$this->status = "De #tag is verwijderd.";
		}
		else{
			$this->status = "De #tag kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
