<?

class motdList extends control {

    function Run() {

        global $XCOW_B;
        
	$motdList = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	$this->mode = $this->ses['request']['param']['mode'];

	# get motd list
	$motdList = UserApiListActivityWithQuery("userId=".$this->userId."&title=motd&title_match=exact&order=date&direction=desc&limit=1");
	if (count($motdList) == 0) {
		$this->ses['response']['param']['firstMotd'] = 1;
	}
	else {
		$this->ses['response']['param']['firstMotd'] = 0;
		$this->ses['response']['param']['motd'] = $motdList[key($motdList)];
	}

	$this->ses['response']['param']['mode'] = $this->mode;

     }

}

?>
