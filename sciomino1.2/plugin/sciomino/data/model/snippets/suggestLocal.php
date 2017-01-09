<?

class suggestLocal extends control {

    function Run() {

        global $XCOW_B;
    
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
 
	// what?
	$this->type = $this->ses['request']['param']['type'];
	$this->subtype = $this->ses['request']['param']['subtype'];
	$this->query = $this->ses['request']['param']['query'];

	// frontend uses term...
	$this->term = $this->ses['request']['param']['term'];

	if (empty($this->query)) {
		$this->query = $this->term;
	}

	# get local suggest list
	$lSearchList = array();
	$lQuery = array();
	$lQuery['context'] = $this->type;
	if (isset ($this->subtype)) {
		$lQuery['sub'] = $this->subtype;
	}
	$lQuery['userId'] = $this->userId;
	$lQuery['limit'] = 10;
	$lQuery['start'] = $this->query;
	$lSearchList = UserApiListListAll($lQuery);

	# merge
	$suggestList = array();
	foreach ($lSearchList[$this->type] as $sKey => $sVal) {
		if ($this->type == "hometown" || $this->type == "workplace") {
			#strip country code: is everything after last ','
			$parts = array();
			$parts = explode(',', $sKey, -1);
			$sKey = implode(',', $parts);
		}
		$suggestList[]['name'] = $sKey;
	}

	# content
	$this->ses['response']['param']['suggestList'] = $suggestList;

     }

}

?>
