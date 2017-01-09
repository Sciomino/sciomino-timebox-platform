<?

class hobbyList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get hobby list
	$hobbyList = ScioMinoApiListHobby($this->userId);

	$this->ses['response']['param']['hobbyList'] = $hobbyList;

     }

}

?>
