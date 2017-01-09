<?

class listDeleteUser extends control {

    function Run() {

        global $XCOW_B;

	$insertId = 0;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->user = makeIntString($this->ses['request']['param']['user']);
	$this->group = makeIntString($this->ses['request']['param']['group']);

	//
	// check fields?
	//
	# get personal list
	$query = "userId=".$this->userId;
	$this->list = UserApiGroupListWithQuery($query);
	# get public list
	$query = "type=public";
	$this->publicList = UserApiGroupListWithQuery($query);
	$mergedList = $this->list + $this->publicList;
	if (! array_key_exists($this->group, $mergedList)) {
		$this->status = "access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

		// delete
		$deleteId = UserApiGroupDeleteUser($this->group, $this->user);
		if ($deleteId != 0) {
			$this->status = "De gebruiker is verwijderd.";
		}
		else{
			$this->status = "De gebruiker kon niet verwijderd worden.";
		}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
