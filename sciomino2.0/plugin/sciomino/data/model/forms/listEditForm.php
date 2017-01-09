<?

class listEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->list = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->listId = makeIntString($this->ses['request']['param']['listId']);

	$this->list['name'] = $this->ses['request']['param']['com_name'];
	$this->list['type'] = "private";

	//
	// check fields?
	//
	$input = array($this->list['name']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	// allow update?
	$query = "userId=".$this->userId;
	if (! array_key_exists($this->listId, UserApiGroupListWithQuery($query) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$listId = UserApiGroupUpdate($this->listId, $this->list);
		if ($listId != 0) {
			$this->status = "De lijst is bewerkt.";
		}
		else{
			$this->status = "De lijst kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['listId'] = $listId;
		$this->ses['response']['param']['listName'] = $this->list['name'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/listEditForm2.php';
        }
        
	// show the form
        else {
		$this->list = UserApiGroupListById($this->listId);
		$this->ses['response']['param']['list'] = $this->list[$this->listId];
		$this->ses['response']['param']['listId'] = $this->listId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
