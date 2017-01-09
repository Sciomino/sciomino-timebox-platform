<?

class userSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->user = array();
	$userId = 0;

	#
	# get params
	#
	$this->user['firstName'] = $this->ses['request']['param']['firstName'];
	$this->user['lastName'] = $this->ses['request']['param']['lastName'];
	$this->user['loginName'] = $this->ses['request']['param']['loginName'];
	$this->user['pageName'] = $this->ses['request']['param']['pageName'];

	$this->reference = $this->ses['request']['param']['reference'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = '';}	

        #
        # check reference
        #
        if (! isset($this->reference) ) {
		$this->status = "403 Unauthorized";
        }

	#
	# NEW USER
	#
	if (! $this->status) {

		$userId = UserInsert($this->user, $this->reference, $this->access);

		if ($userId == 0) {
 			$this->status = "500 Internal Error";
		}
		else {
			$activity= array();
			$activity['title'] = "save_user";
			$activity['description'] = $userId;
			$activity['priority'] = 30;
			$activity['url'] = "";
			UserActivityInsert($activity, $userId, 1);
		}

	}

	#
	# Content
	#
        $this->ses['response']['param']['userId'] = $userId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
