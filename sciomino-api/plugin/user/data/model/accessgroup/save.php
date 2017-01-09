<?

class accessGroupSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->accessGroup = array();
	$accessGroupId = 0;

	#
	# get params
	#
	$this->accessGroup['name'] = $this->ses['request']['param']['name'];
	$this->accessGroup['level'] = $this->ses['request']['param']['level'];

	#
	# NEW SETTINGS
	#
	if (! $this->status) {

		$accessGroupId = AccessGroupInsert($this->accessGroup);

        	if ($accessGroupId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['accessGroupId'] = $accessGroupId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
