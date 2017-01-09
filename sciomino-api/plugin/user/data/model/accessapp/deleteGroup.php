<?

class appDeleteGroup extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$groups = array();
	$apps = array();
	
	#
	# get params
	# - accessapp/deleteGroup?group[ID1]&group[ID2]&app[IDx]&app[IDy]
	#
	$this->groupIdList = $this->ses['request']['param']['group'];
	$this->appIdList = $this->ses['request']['param']['app'];

	#
	# create product list
	#
        if (isset ($this->groupIdList)) {
		foreach (array_keys($this->groupIdList) as $aKey) {
		        $groups[] = $aKey;
		}
	}
        if (isset ($this->appIdList)) {
		foreach (array_keys($this->appIdList) as $bKey) {
		        $apps[] = $bKey;
		}
	}

	#
	# DELETE
	#
	if ((! $this->status) && (count($groups) > 0) && (count($apps) > 0) ) {
	
		$this->status = AccessAppDeleteGroup($apps, $groups);

        }

	#
	# Content
	#
	$this->ses['response']['param']['accessApps'] = $apps;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
