<?

class accessAppSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->accessApp = array();
	$accessAppId = 0;

	#
	# get params
	#
	$this->accessApp['name'] = $this->ses['request']['param']['name'];
	$this->accessApp['key'] = $this->ses['request']['param']['key'];

	#
	# NEW SETTINGS
	#
	if (! $this->status) {

		$accessAppId = AccessAppInsert($this->accessApp);

        	if ($accessAppId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['accessAppId'] = $accessAppId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
