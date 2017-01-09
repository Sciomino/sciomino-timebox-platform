<?

class accessRuleSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->accessRule = array();
	$accessRuleId = 0;

	#
	# get params
	#
	$this->accessRule['name'] = $this->ses['request']['param']['name'];
	$this->accessRule['value'] = $this->ses['request']['param']['value'];

	#
	# NEW SETTINGS
	#
	if (! $this->status) {

		$accessRuleId = AccessRuleInsert($this->accessRule);

        	if ($accessRuleId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['accessRuleId'] = $accessRuleId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
