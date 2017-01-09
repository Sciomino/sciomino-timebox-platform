<?

class getIds extends control {

    function Run() {

        global $XCOW_B;

		$this->offset = $this->ses['request']['param']['offset'];
		if (! isset($this->offset)) {$this->offset = 0;}
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 10;}

		# mode:
		# - inactive: accessId=4 in user api && SessionActive=0 in session db
		# - apponly: accessId=4 in user api && SessionActive=1 in session db
		# - others: NOT accessId=4 in user api (&& mode=active in user api)
		$this->mode = $this->ses['request']['param']['mode'];
		if (! isset($this->mode)) {$this->mode = 'all';}

		# get Id's from offset to limit
		$userList = array();
		$userIdList = array();
		if ($this->mode == "inactive" || $this->mode == "apponly") {
			$userList = UserApiListUserWithQuery("accessId=4&order=id&direction=asc&format=short&offset=".$this->offset."&limit=".$this->limit);
		}
		else {
			$userList = UserApiListUserWithQuery("mode=active&accessId=4&accessId_match=not&order=id&direction=asc&format=short&offset=".$this->offset."&limit=".$this->limit);
		}
		
		# no more output
		if (count($userList) == 0) {
			$userIdList[] = 0;
		}
		foreach ($userList as $user) {
			if ($this->mode == "inactive") {
				# double check inactivity in session
				if (isActiveFromUserId($user['Reference']) == 0) {
					$userIdList[] = $user['Reference'];
				}
			}
			elseif ($this->mode == "apponly") {
				# should have active session
				if (isActiveFromUserId($user['Reference']) == 1) {
					$userIdList[] = $user['Reference'];
				}
			}
			else {
				# need id's not userId's
				$userIdList[] = $user['Reference'];
			}
		}

		# output
		$this->ses['response']['param']['status'] = implode(",",$userIdList);

    }

}

?>
