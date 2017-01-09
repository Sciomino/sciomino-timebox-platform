<?

class knowledgeList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get knowledge list
	$knowledgeList = ScioMinoApiListKnowledge($this->userId);

	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;

     }

}

?>
