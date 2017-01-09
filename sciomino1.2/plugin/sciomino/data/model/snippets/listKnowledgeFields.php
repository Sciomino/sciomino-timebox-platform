<?

class listKnowledgeFields extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->format = $this->ses['request']['param']['format'];
 	$this->start = $this->ses['request']['param']['start'];

 	$this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 1000;}

	// init
	$query = array();
	$query['context'] = "knowledge";
	#$query['userId'] = $this->userId;
	$query['limit'] = $this->limit;
	if ($this->format == "cloud") {
		$query['sort'] = "num";
	}
        if (isset($this->start)) {
		$query['start'] = $this->start;
	}

	$searchList = array();
	$knowledgeList = array();

	// get plain searchList
	$searchList = UserApiListListAll($query, "SC_UserApiListListAll_knowledge_".$this->format."_".$this->start."_".$this->limit);

	$knowledgeList = $searchList['knowledge'];

	$minVal = 0;
	$maxVal = 0;
	foreach ($searchList['knowledge']as $key => $val) {
		if ($minVal > $val || $minVal == 0) {
			$minVal = $val;
		}
		if ($maxVal < $val) {
			$maxVal = $val;
		}
	}

	$this->ses['response']['param']['minVal'] = $minVal;
	$this->ses['response']['param']['maxVal'] = $maxVal;
	$this->ses['response']['param']['interVal'] = intval(($maxVal - $minVal) / 5);

	if ($this->format == "cloud") {
		uksort($knowledgeList, "strnatcasecmp");
		#ksort($knowledgeList);
 	}
	else {
		uksort($knowledgeList, "strnatcasecmp");
		#array_multisort(array_values($knowledgeList), SORT_DESC, array_keys($knowledgeList), SORT_ASC, $knowledgeList); 
	}

	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;

	$thereIsMore = 1;
	if (count($knowledgeList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	$this->ses['response']['param']['start'] = $this->start;
	$this->ses['response']['param']['newLimit'] = $this->limit + 50;

	if ($this->format == "cloud") {
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/listKnowledgeFieldsCloud.php';
	}

     }

}

?>
