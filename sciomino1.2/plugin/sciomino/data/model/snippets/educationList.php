<?

class educationList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get experience list
	# $experienceList = ScioMinoApiListEducation($this->userId);
	$experienceList = UserApiListSectionWithQuery("experience", $this->userId, "name=Education&name_match=exact&order=annotation/subject");

	$this->ses['response']['param']['educationList'] = $experienceList;

     }

}

?>
