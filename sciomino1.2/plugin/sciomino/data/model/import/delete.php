<?

class importDelete extends control {

	#
	# This deletes not active users that did not activate their account within 15 days
	#
    function Run() {

        global $XCOW_B;

		# who?
		$this->id = $this->ses['request']['param']['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		# mode=delete
		$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}

		# tell time
		$mailInterval = 15;
		$timeStamp = time() - (24 * 60 * 60 * $mailInterval);
		
		# go if user exists
		if ($this->userId != 0) {
			
			# make sure the user was not active for the given period of time
			if (isActiveFromUserId($this->id) == 0 && getCreatetimeFromUserId($this->id) < $timeStamp) {
				
				# delete USER: only in delete mode
				if ($this->mode == "delete") {							

					# user should be removed from index first!
					UserApiListListDelete($this->userId);
												
					# delete from user api
					UserApiDeleteUser($this->userId);

					# deactivate OR delete from session
					# - for now... delete, there is not an 'activate session' implemented in this script
					#deactivateSession($userName);
					registerDelete($this->id);					
					
					log2file("User deleted: ".$this->id);
					$status = "User $this->id deleted";

				}
				else {
					echo " *** USER $this->id would be deleted when mode was set correct *** ";
				}

			}
			else {
				echo " *** USER ACTIVE *** ";

			}
			
		}
		else {
			echo " *** USER UNKNOWN *** ";
		}

	}
	
}

?>

