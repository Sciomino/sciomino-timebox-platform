<?

class accessAppUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->accessApp = array();
	$accessApps = array();

	$where = "";

	#
	# get params
	# - accessApp/ID/update
	# - accessApp/update?accessApp[ID1]&accessApp[ID2]
	#
	$this->accessAppId = $this->ses['request']['REST']['param'];
	$this->accessAppIdList = $this->ses['request']['param']['accessapp'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->accessApp['name'] = $this->ses['request']['param']['name']; }
	if (isset($this->ses['request']['param']['key'])) { $this->accessApp['key'] = $this->ses['request']['param']['key']; }

	#
	# create accessApp list
	#
        if (isset ($this->accessAppId)) {
                $accessApps[] = $this->accessAppId;
        }

        if (isset ($this->accessAppIdList)) {
                foreach (array_keys($this->accessAppIdList) as $aKey) {
                        $accessApps[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($accessApps) > 0)) {
	
		$this->status = AccessAppUpdate($accessApps, $this->accessApp);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['accessApps'] = $accessApps;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
