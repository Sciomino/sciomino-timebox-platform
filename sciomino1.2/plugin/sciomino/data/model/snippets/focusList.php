<?

class focusList extends control {

    function Run() {

        global $XCOW_B;
        
	$focusList = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get focus list
	$query = "name=focus&userId=".$this->userId;
	$focusList = UserApiListDataWithQuery($query);

	$this->ses['response']['param']['focusList'] = $focusList;

     }

}

?>
