<?

class groupUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->group = array();
	$groups = array();

	$where = "";

	#
	# get params
	# - group/ID/update
	# - group/update?group[ID1]&group[ID2]
	#
	$this->groupId = $this->ses['request']['REST']['param'];
	$this->groupIdList = $this->ses['request']['param']['group'];

	# references
	# $this->userId = $this->ses['request']['param']['userId'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->group['name'] = $this->ses['request']['param']['name']; } 
	if (isset($this->ses['request']['param']['description'])) { $this->group['description'] = $this->ses['request']['param']['description']; }
	if (isset($this->ses['request']['param']['type'])) { $this->group['type'] = $this->ses['request']['param']['type']; }
	if (isset($this->ses['request']['param']['access'])) { $this->group['access'] = $this->ses['request']['param']['access']; }

	#
	# create group list
	#
        if (isset ($this->groupId)) {
                $groups[] = $this->groupId;
        }

        if (isset ($this->groupIdList)) {
                foreach (array_keys($this->groupIdList) as $aKey) {
                        $groups[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($groups) > 0)) {
	
		$this->status = UserGroupUpdate($groups, $this->group);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['groups'] = $groups;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
