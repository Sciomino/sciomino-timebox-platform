<?

class getIds extends control {

    function Run() {

        global $XCOW_B;

		$this->offset = $this->ses['request']['param']['offset'];
		if (! isset($this->offset)) {$this->offset = 0;}
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 10;}

		$this->mode = $this->ses['request']['param']['mode'];
		if (! isset($this->mode)) {$this->mode = 'all';}

		# get Id's from offset to limit
		$userList = array();
		$userIdList = array();
		$userList = UserApiListUserWithQuery("order=id&direction=asc&format=short&offset=".$this->offset."&limit=".$this->limit);
		
		# no more output
		if (count($userList) == 0) {
			$userIdList[] = 0;
		}
		foreach ($userList as $user) {
			if ($this->mode == "inactive") {
				if (isActiveFromUserId($user['Reference']) == 0) {
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
