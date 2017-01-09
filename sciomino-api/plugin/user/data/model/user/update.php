<?

class userUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->user = array();
	$users = array();

	$where = "";

	#
	# get params
	# - user/ID/update
	# - user/update?user[ID1]&user[ID2]
	#
	$this->userId = $this->ses['request']['REST']['param'];
	$this->userIdList = $this->ses['request']['param']['user'];

	# user changes
	if (isset($this->ses['request']['param']['firstName'])) { $this->user['firstName'] = $this->ses['request']['param']['firstName']; } 
	if (isset($this->ses['request']['param']['lastName'])) { $this->user['lastName'] = $this->ses['request']['param']['lastName']; }
	if (isset($this->ses['request']['param']['loginName'])) { $this->user['loginName'] = $this->ses['request']['param']['loginName']; }
	if (isset($this->ses['request']['param']['pageName'])) { $this->user['pageName'] = $this->ses['request']['param']['pageName']; }

	#
	# create user list
	#
        if (isset ($this->userId)) {
                $users[] = $this->userId;
        }

        if (isset ($this->userIdList)) {
                foreach (array_keys($this->userIdList) as $aKey) {
                        $users[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($users) > 0)) {
	
		$this->status = UserUpdate($users, $this->user);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['users'] = $users;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
