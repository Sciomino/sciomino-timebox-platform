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

		$profileId = UserProfileInsert($this->profile, $this->object, $this->object_id, $this->access);

        	if ($profileId == 0) {
 			$this->status = "500 Internal Error";
        	}
        	else {
				// only save activity for user profiles
				if ($this->object == "user") {
					$userId = UserProfileGetUserId($this->object, $profileId);
					$activity= array();
					$activity['title'] = "save_user_profile_".$this->profile['group'];
					$activity['description'] = $profileId;
					$activity['priority'] = 30;
					$activity['url'] = "";
					UserActivityInsert($activity, $userId, 1);
				}
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
