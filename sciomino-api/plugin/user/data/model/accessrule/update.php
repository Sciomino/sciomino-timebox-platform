<?

class accessRuleUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->accessRule = array();
	$accessRules = array();

	$where = "";

	#
	# get params
	# - accessRule/ID/update
	# - accessRule/update?accessRule[ID1]&accessRule[ID2]
	#
	$this->accessRuleId = $this->ses['request']['REST']['param'];
	$this->accessRuleIdList = $this->ses['request']['param']['accessrule'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->accessRule['name'] = $this->ses['request']['param']['name']; }
	if (isset($this->ses['request']['param']['value'])) { $this->accessRule['value'] = $this->ses['request']['param']['value']; }

	#
	# create accessRule list
	#
        if (isset ($this->accessRuleId)) {
                $accessRules[] = $this->accessRuleId;
        }

        if (isset ($this->accessRuleIdList)) {
                foreach (array_keys($this->accessRuleIdList) as $aKey) {
                        $accessRules[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($accessRules) > 0)) {
	
		$this->status = AccessRuleUpdate($accessRules, $this->accessRule);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['accessRules'] = $accessRules;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
