<?php

# availability is:
# - group=availability

class userAvailabilityListNetwork extends control {

    function Run() {

        global $XCOW_B;
		$networkList = array();
       
		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		# get network list
		$query1 = "type=availability-customer&order=name";
		$networkList1 = UserApiGroupListWithQuery($query1);
		#$query2 = "type=availability-prospect&order=name";
		#$networkList2 = UserApiGroupListWithQuery($query2);
		#$networkList = $networkList1 + $networkList2;
		$networkList = $networkList1;

		# do something with network
		foreach ($networkList as $networkKey => $networkVal) {
			# add logo
			$networkList[$networkKey]['photoStream'] = base64_encode(file_get_contents($XCOW_B['upload_destination_dir']."/networks/".strtolower($networkVal['Name']).".png"));
			
			# is checked?
			$networkList[$networkKey]['checked'] = 0;
			if (is_array($networkVal['User'])) {	
				foreach ($networkVal['User'] as $userKey => $userVal) {
					if ($this->userId == $userKey) {	
						$networkList[$networkKey]['checked'] = 1;
						break;		
					}
				}
			}
		}

		$this->ses['response']['param']['user'] = $this->userId;
		$this->ses['response']['param']['networkList'] = $networkList;

		
		// allow resource, for testing only!!!
		$this->ses['response']['header'] = "Access-Control-Allow-Origin:*"; 

     }

    function GetHeader() {
        #$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
        return ($this->ses['response']['header']);
    }

}
?>
