<?

class sessionView extends control {

    function Run() {

        global $XCOW_B;
        
        $this->id = $this->ses['id'];
	$this->userInfo = current(UserApiListUserWithQuery("reference=".$this->id."&format=short", "SC_UserApiListUserWithQuery_".$this->id."_short"));

	if (isset($this->id) ) {
		$this->ses['response']['param']['id'] = $this->id;

		$name = getUserDisplayNameFromUserId($this->id);
		if ($name == '') { $name = getUserNameFromUserId($this->id);}
		$this->ses['response']['param']['user'] = $name;

		$this->ses['response']['param']['apiUser'] = $this->userInfo['Id'];
		$this->ses['response']['param']['apiUserPhoto'] = $this->userInfo['photo'];
	}
	else {
		$this->ses['response']['param']['id'] = 0;
	}

    }

}

?>
