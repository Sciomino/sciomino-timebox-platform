<?

class websiteList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	# get publication list
	$publicationList = ScioMinoApiListWebsite($this->userId);

	$this->ses['response']['param']['websiteList'] = $publicationList;

     }

}

?>
