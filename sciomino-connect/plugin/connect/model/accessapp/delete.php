<?

class accessAppDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$accessApps = array();

	#
	# get params
	# - accessApp/ID/delete
	# - accessApp/delete?accessApp[ID1]&accessApp[ID2]
	#
	$this->accessAppId = $this->ses['request']['REST']['param'];
	$this->accessAppIdList = $this->ses['request']['param']['accessapp'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = AccessAppDelete($accessApps);

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
