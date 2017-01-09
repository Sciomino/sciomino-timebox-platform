<?

class activityDelete extends control {

    function Run() {

        global $XCOW_B;

	$activityList = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->activityId = makeIntString($this->ses['request']['param']['activityId']);

	// allow delete?
	$query = "userId=".$this->userId;
	$activityList = UserApiListActivityWithQuery($query);
	if (array_key_exists($this->activityId, $activityList)) {
		// delete 
		if (UserApiDeleteActivity($this->activityId, "SC_UserApiListActivityWithQuery_") != 0) {
			$this->status = "De activity is verwijderd.";
		}
		else{
			$this->status = "De activity kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
