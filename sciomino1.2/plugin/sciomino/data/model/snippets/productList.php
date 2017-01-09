<?

class productList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get experience list
	# $experienceList = ScioMinoApiListProduct($this->userId);
	$experienceList = UserApiListSectionWithQuery("experience", $this->userId, "name=Product&name_match=exact&order=annotation/subject");

	$this->ses['response']['param']['productList'] = $experienceList;

     }

}

?>
