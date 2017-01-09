<?

class profileSave extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->profile = array();
	$profileId = 0;

	#
	# get params
	#
        $this->object = $this->ses['request']['param']['object'];
        $this->object_id = $this->ses['request']['param']['object_id'];

	$this->profile['name'] = $this->ses['request']['param']['name'];
	$this->profile['group'] = $this->ses['request']['param']['group'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = '';}	

	#
	# NEW Profile
	#
	if (! $this->status) {

		$profileId = ProfileInsert($this->profile, $this->object, $this->object_id, $this->access);

        	if ($profileId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['profileId'] = $profileId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
