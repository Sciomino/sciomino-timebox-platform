<?

class educationDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->educationId = makeIntString($this->ses['request']['param']['educationId']);

	// allow delete?
	$this->education = ScioMinoApiGetEducation($this->userId, $this->educationId);
	if (array_key_exists($this->educationId, $this->education)) {
		// delete 
		if (ScioMinoApiDeleteEducation($this->userId, $this->educationId) != 0) {
			$this->status = "De opleidingservaring is verwijderd.";
		}
		else{
			$this->status = "De opleidingservaring kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
