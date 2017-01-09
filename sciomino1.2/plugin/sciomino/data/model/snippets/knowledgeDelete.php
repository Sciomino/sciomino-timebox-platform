<?

class knowledgeDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->knowledgeId = makeIntString($this->ses['request']['param']['knowledgeId']);

	// allow delete?
	$this->knowledge = ScioMinoApiGetKnowledge($this->userId, $this->knowledgeId);
	if (array_key_exists($this->knowledgeId, $this->knowledge)) {
		// delete 
		if (ScioMinoApiDeleteKnowledge($this->userId, $this->knowledgeId) != 0) {
			$this->status = "Het kennisveld is verwijderd.";
		}
		else{
			$this->status = "Het kennisveld kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
