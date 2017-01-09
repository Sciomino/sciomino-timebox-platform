<?

class groupSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->group = array();
	$groupId = 0;

	#
	# get params
	#
	$this->group['name'] = $this->ses['request']['param']['name'];
	$this->group['description'] = $this->ses['request']['param']['description'];
	$this->group['type'] = $this->ses['request']['param']['type'];

	# reference
        $this->userId = $this->ses['request']['param']['userId'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = '';}	

	#
	# NEW GROUP
	#
	if (! $this->status) {

		$groupId = UserGroupInsert($this->group, $this->userId, $this->access);

        	if ($groupId == 0) {
			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['groupId'] = $groupId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
