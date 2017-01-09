<?

class listExperienceFields extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->format = $this->ses['request']['param']['format'];
 	$this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 500;}

	// init
	$query = array();
	$query['context'] = "experience";
	#$query['userId'] = $this->userId;
	if ($this->limit != 0) {
		$query['limit'] = $this->limit;
	}
	$searchList = array();

	// get plain searchList
	$searchList = UserApiListListAll($query);
	$searchList = UserApiListListAll($query, "SC_UserApiListListAll_experience_".$this->limit);

	if ($this->format == "short") {
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/listExperienceFieldsShort.php';
	}

        uksort($searchList['company'], "strnatcasecmp");
        uksort($searchList['event'], "strnatcasecmp");
        uksort($searchList['education'], "strnatcasecmp");
        uksort($searchList['product'], "strnatcasecmp");

	#array_multisort(array_values($searchList['company']), SORT_DESC, array_keys($searchList['company']), SORT_ASC, $searchList['company']);
	#array_multisort(array_values($searchList['event']), SORT_DESC, array_keys($searchList['event']), SORT_ASC, $searchList['event']);
	#array_multisort(array_values($searchList['education']), SORT_DESC, array_keys($searchList['education']), SORT_ASC, $searchList['education']);
	#array_multisort(array_values($searchList['product']), SORT_DESC, array_keys($searchList['product']), SORT_ASC, $searchList['product']);

	$this->ses['response']['param']['limit'] = $this->limit;
	$this->ses['response']['param']['companyList'] = $searchList['company'];
	$this->ses['response']['param']['eventList'] = $searchList['event'];
	$this->ses['response']['param']['educationList'] = $searchList['education'];
	$this->ses['response']['param']['productList'] = $searchList['product'];

	$thereIsMore = 1;
	if (count($searchList['company']) < $this->limit && count($searchList['event']) < $this->limit && count($searchList['education']) < $this->limit && count($searchList['product']) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

     }

}

?>
