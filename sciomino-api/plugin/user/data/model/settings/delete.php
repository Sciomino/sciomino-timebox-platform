<?

class settingsDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$settingss = array();

	#
	# get params
	# - settings/ID/delete
	# - settings/delete?settings[ID1]&settings[ID2]
	#
	$this->settingsId = $this->ses['request']['REST']['param'];
	$this->settingsIdList = $this->ses['request']['param']['settings'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserSettingsDelete($settingss);

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
