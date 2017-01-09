<?php

class userAvailabilityUpdateNetwork extends control {

    function Run() {

        global $XCOW_B;
        
        $this->network = array();
        $status = 0;
        $message = "";
        
		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		// params
		$this->network['id'] = makeIntString($this->ses['request']['param']['id']);
		$this->network['value'] = makeIntString($this->ses['request']['param']['value']);

		// is user member of group?
		$userList = current(UserApiListUserWithQuery("reference=".$this->id));
		$isMember= 0;
		if (is_array($userList['GroupMember']) && array_key_exists($this->network['id'], $userList["GroupMember"])) {
			$isMember = 1;
		}
		
		// do it
		// - if $isMember & ! $value then DELETE
		// - if ! isMember & $value then CREATE
		// - else nothing todo
		if ($isMember && $this->network['value']==0) {
			$deleteId = UserApiGroupDeleteUser($this->network['id'], $this->userId);
			if ($deleteId != 0) {
				$status = 1;
			}
			else{
				$message="error: could not complete request";
			}
		}
		elseif (! $isMember && $this->network['value']==1) {
			$insertId = UserApiGroupSaveUser($this->network['id'], $this->userId);
			if ($insertId != 0) {
				$status = 1;
			}
			else{
				$message="error: could not complete request";
			}
		}
		else {
			$status = 1;
			$message="nothing to do";
		}
	
        $this->ses['response']['param']['network'] = $this->network['id'];
        $this->ses['response']['param']['status'] = $status;
        $this->ses['response']['param']['message'] = $message;

		
		// allow resource, for testing only!!!
		$this->ses['response']['header'] = "Access-Control-Allow-Origin:*"; 

     }

/*
	function updateTimestamp() {
		$availabilityList = ScioMinoApiListAvailability($this->userId);
		$currentAvailability = current($availabilityList);

		if (isset($currentAvailability['status'])) {
			$newAvailability = array();
			$newAvailability['timestamp'] = time();
			
			# overwrite timestamp
			if ($newAvailability['timestamp'] > $currentAvailability['timestamp']) {
				$availabilityId = $currentAvailability['Id'];
				$availabilityId = ScioMinoApiUpdateAvailability($newAvailability, $this->userId, $availabilityId);
			}
		}
	}
*/

    function GetHeader() {
        #$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
        return ($this->ses['response']['header']);
    }

}

?>
