<?

class activitySave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->activity = array();
	$activityId = 0;

	#
	# get params
	#
	$this->activity['title'] = $this->ses['request']['param']['title'];
	$this->activity['description'] = $this->ses['request']['param']['description'];
	$this->activity['priority'] = $this->ses['request']['param']['priority'];
	$this->activity['url'] = $this->ses['request']['param']['url'];

	# reference
        $this->userId = $this->ses['request']['param']['userId'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = 1;}	

	#
	# NEW CONTACT
	#
	if (! $this->status) {

		$activityId = UserActivityInsert($this->activity, $this->userId, $this->access);

        	if ($activityId == 0) {
			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['activityId'] = $activityId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
