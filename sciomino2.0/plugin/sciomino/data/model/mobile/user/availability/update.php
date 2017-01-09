<?php

class userAvailabilityUpdate extends control {

    function Run() {

        global $XCOW_B;
        
        $this->availability = array();
        $status = 0;
        $message = "";
        
		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		// params
		$validStrings = array("available", "unavailable");
		$this->availability['status'] = makeStringString($this->ses['request']['param']['status'],$validStrings,128);
		$this->availability['hours'] = makeIntString($this->ses['request']['param']['hours']);
		$this->availability['days'] = makeString($this->ses['request']['param']['days'],128);
		//$this->availability['until'] = makeTimeString($this->ses['request']['param']['until']);
		$this->availability['until'] = $this->ses['request']['param']['until'];
		$this->availability['future-status'] = makeStringString($this->ses['request']['param']['future-status'],$validStrings,128);
		$this->availability['future-hours'] = makeIntString($this->ses['request']['param']['future-hours']);
		$this->availability['future-days'] = makeString($this->ses['request']['param']['future-days'],128);
		//$this->availability['future-until'] = makeTimeString($this->ses['request']['param']['future-until']);
		$this->availability['future-until'] = $this->ses['request']['param']['future-until'];

		// add timestamp for syncing
		$this->availability['timestamp'] = time();
		
		# get availability list
		$availabilityList = ScioMinoApiListAvailability($this->userId);
		$currentAvailability = current($availabilityList);

		if (! isset($currentAvailability['status'])) {
			# first time...
			$availabilityId = ScioMinoApiSaveAvailability($this->availability, $this->userId, '1');
		}
		else {
			# overwrite personal availability, if timestamp is newer
			if (! isset($currentAvailability['timestamp']) || $this->availability['timestamp'] > $currentAvailability['timestamp']) {
				$availabilityId = $currentAvailability['Id'];
				$availabilityId = ScioMinoApiUpdateAvailability($this->availability, $this->userId, $availabilityId);
			}
		}

		# are we good?
		if ($availabilityId > 0) {
			$status = 1;
		}
		else{
			$message = language('sciomio_text_user_profile_edit_status_wrong');
		}

        $this->ses['response']['param']['status'] = $status;
        $this->ses['response']['param']['message'] = $message;

		
		// allow resource, for testing only!!!
		$this->ses['response']['header'] = "Access-Control-Allow-Origin:*"; 

     }

    function GetHeader() {
        #$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
        return ($this->ses['response']['header']);
    }

}

?>
