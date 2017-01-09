<?

class accessRuleDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$accessRules = array();

	#
	# get params
	# - accessRule/ID/delete
	# - accessRule/delete?accessRule[ID1]&accessRule[ID2]
	#
	$this->accessRuleId = $this->ses['request']['REST']['param'];
	$this->accessRuleIdList = $this->ses['request']['param']['accessrule'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = AccessRuleDelete($accessRules);

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
