<?

class settingsSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->settings = array();
	$settingsId = 0;

	#
	# get params
	#
	$this->settings['name'] = $this->ses['request']['param']['name'];
	$this->settings['value'] = $this->ses['request']['param']['value'];

	# reference
        $this->userId = $this->ses['request']['param']['userId'];

	#
	# NEW SETTINGS
	#
	if (! $this->status) {

		$settingsId = UserSettingsInsert($this->settings, $this->userId);

        	if ($settingsId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['settingsId'] = $settingsId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
