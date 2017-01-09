<?

class listLocalFields extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->type = $this->ses['request']['param']['type'];
	$this->subtype = $this->ses['request']['param']['subtype'];
 	$this->start = $this->ses['request']['param']['start'];

 	$this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 1000;}

	// init
	$query = array();
	$query['context'] = $this->type;
	if (isset ($this->subtype)) {
		$query['sub'] = $this->subtype;
	}
	#$query['userId'] = $this->userId;
	$query['limit'] = $this->limit;
        if (isset($this->start)) {
		$query['start'] = $this->start;
	}

	// get plain searchList
	$searchList = array();
	//$searchList = UserApiListListAll($query);
	$searchList = UserApiListListAll($query, "SC_UserApiListListAll_".$this->type."_".$this->subtype."_".$this->start."_".$this->limit);

	$localList = $searchList[$this->type];
	uksort($localList, "strnatcasecmp");
	#array_multisort(array_values($localList), SORT_DESC, array_keys($localList), SORT_ASC, $localList);
	$this->ses['response']['param']['localType'] = $this->type;
	$this->ses['response']['param']['localList'] = $localList;

	$thereIsMore = 1;
	if (count($localList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	$this->ses['response']['param']['start'] = $this->start;
	$this->ses['response']['param']['newLimit'] = $this->limit + 50;

     }

}

?>
