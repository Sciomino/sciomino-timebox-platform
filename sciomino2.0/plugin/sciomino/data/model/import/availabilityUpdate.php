<?

class availabilityUpdate extends control {

    function Run() {

        global $XCOW_B;
        
		// who?
		$this->id = $this->ses['request']['param']['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		// mode=update: update availability
		// default: display
		$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		
		// init
		// - updateInterval: frequency of availability update (30 days)
		$updateInterval = 30;
			
		// prepare action (but only if userId exists!)
		// 0. do nothing
		// 1. set availability
		// 2. send mail (todo, maybe...)
		$status = "Nothing to be done";
		$action = 0;
		$timestamp = time();
		if ($this->userId != 0) {		
			$action = 1;			
		}
		
		// perform action
		// - create new availability for this user
		if ($action == 1) {
			
			##############
			# AVAILABILITY
			##############
			# get current availability list
			$availabilityList = ScioMinoApiListAvailability($this->userId);
			
			# not everyone updates availability
			if (count($availabilityList) > 0) {
				$currentAvailability = current($availabilityList);
				$availabilityId = $currentAvailability['Id']; 

				# reset availability
				# - if not already unknown
				# - and the timestamp is reached
				if ($currentAvailability['status'] != "" && $currentAvailability['until'] < $timestamp) {
					# set current availability to future availability
					$newAvailability = array();
					$newAvailability['status'] = $currentAvailability['future-status'];
					$newAvailability['days'] = $currentAvailability['future-days'];
					$newAvailability['hours'] = $currentAvailability['future-hours'];
					// a clean until date (to fix those 0 timestamps...)
					//$newAvailability['until'] = $currentAvailability['until'] + (24 * 60 * 60 * $updateInterval);
					$newAvailability['until'] = $timestamp + (24 * 60 * 60 * $updateInterval);
					$newAvailability['future-status'] = "";
					$newAvailability['future-days'] = "";
					$newAvailability['future-hours'] = 0;
					$newAvailability['timestamp'] = $timestamp;
					
					# if unknown:unknown then reset the until
					# - and make sure days and hours are ok...
					if ($newAvailability['status'] == "") {
						$newAvailability['until'] = "";
						
						$newAvailability['days'] = "";
						$newAvailability['hours'] = 0;
					}

					# something wrong with day initialization, fix it here for now
					# - it is a string, so default should be ""
					# - but sometimes the app generate 0 OR undefined
					if ($newAvailability['status'] == "unavailable") {
						$newAvailability['days'] = "";
					}

					// override normal behaviour for debug purposes
					if ($this->mode == "onlyDisplay") {
						$status = "OLD<br/>\n";
						$status = $status . print_r($currentAvailability, true);
						$status = $status . "UPDATE<br/>\n";
						$status = $status . print_r($newAvailability, true);
					}
					else {				
						ScioMinoApiUpdateAvailability($newAvailability, $this->userId, $availabilityId);
						
						log2file("Updated availability data for id: ".$this->id);
						$status = "Updated availability data for id: ".$this->id;
					}
				}
			}
		}

		# output
		$this->ses['response']['param']['status'] = $status;

     }
     
}

?>
