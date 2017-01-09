<?

class profileUpdate extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->profile = array();
	$profiles = array();

	$where = "";

	#
	# get params
	# - profile/ID/update
	# - profile/update?profile[ID1]&profile[ID2]
	#
	$this->profileId = $this->ses['request']['REST']['param'];
	$this->profileIdList = $this->ses['request']['param']['profile'];

        $this->object = $this->ses['request']['param']['object'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->profile['name'] = $this->ses['request']['param']['name']; } 
	if (isset($this->ses['request']['param']['group'])) { $this->profile['group'] = $this->ses['request']['param']['group']; }

	#
	# create profile list
	#
        if (isset ($this->profileId)) {
                $profiles[] = $this->profileId;
        }

        if (isset ($this->profileIdList)) {
                foreach (array_keys($this->profileIdList) as $aKey) {
                        $profiles[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($profiles) > 0)) {
	
		$this->status = UserProfileUpdate($profiles, $this->profile, $this->object);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['profiles'] = $profiles;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
