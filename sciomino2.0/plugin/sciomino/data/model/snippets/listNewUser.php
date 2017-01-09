<?

class listNewUser extends control {

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
	$personal = 0;
	# get personal list
	$query = "userId=".$this->userId;
	$this->list = UserApiGroupListWithQuery($query);
	if (array_key_exists($this->group, $this->list)) {
		$personal = 1;
	}
	$public = 0;
	# get public list
	$query = "type=public";
	$this->publicList = UserApiGroupListWithQuery($query);
	if (array_key_exists($this->group, $this->publicList) && $this->userId == $this->user) {
		$public = 1;
	}

	if ($personal == 0 && $public == 0) {
		$this->status = "access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise nada
        //
        if (! $this->status) {

		// save
		$insertId = UserApiGroupSaveUser($this->group, $this->user);
		if ($insertId != 0) {
			$this->status = "De gebruiker is toegevoegd.";
		}
		else{
			$this->status = "De gebruiker kon niet toegevoegd worden.";
		}

        }
        
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
