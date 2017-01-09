<?

class activityUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->activity = array();
	$activitys = array();

	$where = "";

	#
	# get params
	# - activity/ID/update
	# - activity/update?activity[ID1]&activity[ID2]
	#
	$this->activityId = $this->ses['request']['REST']['param'];
	$this->activityIdList = $this->ses['request']['param']['activity'];

	# references
	# $this->userId = $this->ses['request']['param']['userId'];

	# user changes
	if (isset($this->ses['request']['param']['title'])) { $this->activity['title'] = $this->ses['request']['param']['title']; } 
	if (isset($this->ses['request']['param']['description'])) { $this->activity['description'] = $this->ses['request']['param']['description']; }
	if (isset($this->ses['request']['param']['priority'])) { $this->activity['priority'] = $this->ses['request']['param']['priority']; }
	if (isset($this->ses['request']['param']['url'])) { $this->activity['url'] = $this->ses['request']['param']['url']; }

	#
	# create activity list
	#
        if (isset ($this->activityId)) {
                $activitys[] = $this->activityId;
        }

        if (isset ($this->activityIdList)) {
                foreach (array_keys($this->activityIdList) as $aKey) {
                        $activitys[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($activitys) > 0)) {
	
		$this->status = UserActivityUpdate($activitys, $this->activity);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['activitys'] = $activitys;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
