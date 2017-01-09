<?

class accessGroupUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->accessGroup = array();
	$accessGroups = array();

	$where = "";

	#
	# get params
	# - accessGroup/ID/update
	# - accessGroup/update?accessGroup[ID1]&accessGroup[ID2]
	#
	$this->accessGroupId = $this->ses['request']['REST']['param'];
	$this->accessGroupIdList = $this->ses['request']['param']['accessgroup'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->accessGroup['name'] = $this->ses['request']['param']['name']; }
	if (isset($this->ses['request']['param']['level'])) { $this->accessGroup['value'] = $this->ses['request']['param']['level']; }

	#
	# create accessGroup list
	#
        if (isset ($this->accessGroupId)) {
                $accessGroups[] = $this->accessGroupId;
        }

        if (isset ($this->accessGroupIdList)) {
                foreach (array_keys($this->accessGroupIdList) as $aKey) {
                        $accessGroups[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($accessGroups) > 0)) {
	
		$this->status = AccessGroupUpdate($accessGroups, $this->accessGroup);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['accessGroups'] = $accessGroups;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
