<?

class settingsUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->settings = array();
	$settingss = array();

	$where = "";

	#
	# get params
	# - settings/ID/update
	# - settings/update?settings[ID1]&settings[ID2]
	#
	$this->settingsId = $this->ses['request']['REST']['param'];
	$this->settingsIdList = $this->ses['request']['param']['settings'];

	# references
	# $this->userId = $this->ses['request']['param']['userId'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->settings['name'] = $this->ses['request']['param']['name']; }
	if (isset($this->ses['request']['param']['value'])) { $this->settings['value'] = $this->ses['request']['param']['value']; }

	#
	# create settings list
	#
        if (isset ($this->settingsId)) {
                $settingss[] = $this->settingsId;
        }

        if (isset ($this->settingsIdList)) {
                foreach (array_keys($this->settingsIdList) as $aKey) {
                        $settingss[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($settingss) > 0)) {
	
		$this->status = UserSettingsUpdate($settingss, $this->settings);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['settingss'] = $settingss;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
