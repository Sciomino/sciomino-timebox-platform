<?

class groupDeleteUser extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$groups = array();
	$users = array();
	
	#
	# get params
	# - group/deleteUser?group[ID1]&group[ID2]&user[IDx]&user[IDy]
	#
	$this->groupIdList = $this->ses['request']['param']['group'];
	$this->userIdList = $this->ses['request']['param']['user'];

	#
	# create product list
	#
        if (isset ($this->groupIdList)) {
		foreach (array_keys($this->groupIdList) as $aKey) {
		        $groups[] = $aKey;
		}
	}
        if (isset ($this->userIdList)) {
		foreach (array_keys($this->userIdList) as $bKey) {
		        $users[] = $bKey;
		}
	}

	#
	# DELETE
	#
	if ((! $this->status) && (count($groups) > 0) && (count($users) > 0) ) {
	
		$this->status = UserGroupDeleteUser($groups, $users);

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
