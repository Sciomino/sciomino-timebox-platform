<?

class socialnetworkList extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	# get publication list
	$publicationList = ScioMinoApiListSocialNetwork($this->userId);

	$this->ses['response']['param']['networkList'] = $publicationList;

     }

}

?>
