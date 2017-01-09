<?

class getIds extends control {

    function Run() {

        global $XCOW_B;

		$this->offset = $this->ses['request']['param']['offset'];
		if (! isset($this->offset)) {$this->offset = 0;}
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 10;}

		$this->timestamp = $this->ses['request']['param']['timestamp'];
		if (! isset($this->timestamp)) {$this->timestamp = 0;}

		# about the customer
		# - get customer info
		# - get app info
		$this->customer = $this->ses['request']['param']['customer'];
		$this->customerInfo = $XCOW_B['graph']['customers'][$this->customer];
		$appInfo = GraphSessionGetAppInfo($this->customerInfo["appid"]);

		# do a sanity check, match two configs to make sure the 'appid' is oke
		# - group, in graph.ini
		# - network, in db
		if (isset($this->customer) && array_key_exists($this->customer, $XCOW_B['graph']['customers']) && $this->customerInfo["group"] == $appInfo['network']) {
			# set Timebox api params
			$apiParams = array();
			$apiParams["format"] = "short";
			$apiParams["from"] = $this->timestamp;
			$apiParams["offset"] = $this->offset;
			$apiParams["limit"] = $this->limit;
			
			# get Id's
			# - from offset to limit
			$userList = array();
			$userIdList = array();
			$userList = json_decode(GraphDataLiveUserList($appInfo, $apiParams), TRUE);
			$userList = $userList["content"]["user"];
					
			# no more output
			if (count($userList) == 0) {
				$userIdList[] = 0;
			}
			foreach ($userList as $key => $user) {
				$userIdList[] = $user['id'];
			}
		}
		else {
				$userIdList[] = 0;
		}

		# output
		$this->ses['response']['param']['status'] = implode(",",$userIdList);
    }

}

?>
