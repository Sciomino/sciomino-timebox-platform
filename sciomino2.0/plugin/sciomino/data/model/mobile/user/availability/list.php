<?php

# availability is:
# - status
# - hour
# - days
# - until

class userAvailabilityList extends control {

    function Run() {

        global $XCOW_B;
        
		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		# get availability list
		$availabilityList = ScioMinoApiListAvailability($this->userId);

		if (count($availabilityList) != 0) {
			# there should only be one...
			$availabilityList = current($availabilityList);
		}

		# rename empty status to 'unknown'
		if (! isset($availabilityList['status']) || $availabilityList['status'] == "") {
			$availabilityList['status'] = "unknown";
		}
		if (! isset($availabilityList['future-status']) || $availabilityList['future-status'] == "") {
			$availabilityList['future-status'] = "unknown";
		}
		
		$this->ses['response']['param']['availabilityList'] = $availabilityList;		

		
		// allow resource, for testing only!!!
		$this->ses['response']['header'] = "Access-Control-Allow-Origin:*"; 

     }

    function GetHeader() {
        #$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
        return ($this->ses['response']['header']);
    }

}

?>
