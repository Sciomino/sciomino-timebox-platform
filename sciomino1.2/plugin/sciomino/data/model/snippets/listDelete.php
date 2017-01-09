<?

class listDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->listId = makeIntString($this->ses['request']['param']['listId']);

	// allow delete?
	$query = "userId=".$this->userId;
	$this->list = UserApiGroupListWithQuery($query);
	if (array_key_exists($this->listId, $this->list)) {
		// delete 
		if (UserApiGroupDelete($this->listId) != 0) {
			$this->status = "De lijst is verwijderd.";
		}
		else{
			$this->status = "De lijst kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
