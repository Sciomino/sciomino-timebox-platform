<?

class tagList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get tag list
	$tagList = ScioMinoApiListTag($this->userId);

	$this->ses['response']['param']['tagList'] = $tagList;

     }

}

?>
