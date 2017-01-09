<?

class companyDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->companyId = makeIntString($this->ses['request']['param']['companyId']);

	// allow delete?
	$this->company = ScioMinoApiGetCompany($this->userId, $this->companyId);
	if (array_key_exists($this->companyId, $this->company)) {
		// delete 
		if (ScioMinoApiDeleteCompany($this->userId, $this->companyId) != 0) {
			$this->status = "De bedrijfservaring is verwijderd.";
		}
		else{
			$this->status = "De bedrijfservaring kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>
