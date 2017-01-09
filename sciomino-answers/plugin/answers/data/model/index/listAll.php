<?

class indexListAll extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$knowledgeList = array();

	$hobbyList = array();

	# personal
	$businessunitList = array();
	$workplaceList = array();

	# status & my
	$statusList = array();
	$myList = array();

	# network
	$networkList = array();

	#
	# get params
	#
	$this->userId = $this->ses['request']['param']['userId'];

        $this->context = $this->ses['request']['param']['context'];
        $this->start = $this->ses['request']['param']['start'];
        if (! isset($this->start)) {$this->start = '';}
       
        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 500;}

	$this->listSize = 0;

	# knowledge
	if ($this->context == "knowledge" || $this->context == "all") {
		$knowledgeContext = SearchWordGetWords('knowledge', $this->start);

		foreach (array_keys($knowledgeContext) as $knKey) {
			$knowledgeList[$knowledgeContext[$knKey]['Word']] = $knowledgeContext[$knKey]['Count'];
		}

		arsort($knowledgeList, SORT_NUMERIC);
		$knowledgeList = array_slice($knowledgeList, 0, $this->limit, true);
		$this->listSize = count($knowledgeList);
	}

	# hobby
	if ($this->context == "hobby" || $this->context == "all") {
		$hobbyContext = SearchWordGetWords('hobby', $this->start);

		foreach (array_keys($hobbyContext) as $knKey) {
			$hobbyList[$hobbyContext[$knKey]['Word']] = $hobbyContext[$knKey]['Count'];
		}

		arsort($hobbyList, SORT_NUMERIC);
		$hobbyList = array_slice($hobbyList, 0, $this->limit, true);
		$this->listSize = count($hobbyList);
	}

	# personal 
	/* disabled since version 1.2n
	if ($this->context == "businessunit" || $this->context == "all") {
		# businessunit
		$businessunitContext = SearchWordGetWords('businessunit', $this->start);

		foreach (array_keys($businessunitContext) as $peKey) {
			$businessunitList[$businessunitContext[$peKey]['Word']] = $businessunitContext[$peKey]['Count'];
		}

		arsort($businessunitList, SORT_NUMERIC);
		$businessunitList = array_slice($businessunitList, 0, $this->limit, true);
		$this->listSize = count($businessunitList);
	}
	*/
	/* disabled since version 1.2n
	if ($this->context == "workplace" || $this->context == "all") {
		# workplace
		$workplaceContext = SearchWordGetWords('workplace', $this->start);

		foreach (array_keys($workplaceContext) as $peKey) {
			$workplaceList[$workplaceContext[$peKey]['Word']] = $workplaceContext[$peKey]['Count'];
		}

		arsort($workplaceList, SORT_NUMERIC);
		$workplaceList = array_slice($workplaceList, 0, $this->limit, true);
		$this->listSize = count($workplaceList);
	}
	*/

	# status & my
	if ($this->context == "status" || $this->context == "all") {

		#$statusList['Actueel'] = 6;
		#$statusList['Vandaag'] = 5;
		#$statusList['Deze week'] = 4;
		#$statusList['Deze maand'] = 3;
		#$statusList['Met verhaal'] = 2;
		#$statusList['Alles afgelopen'] = 1;
		$statusList['relevant'] = 8;
		$statusList['open'] = 7;
		$statusList['open_day'] = 6;
		$statusList['open_week'] = 5;
		$statusList['open_month'] = 4;
		$statusList['closed'] = 3;
		$statusList['closed_story'] = 2;
		#$statusList['closed_no_story'] = 1;

		arsort($statusList, SORT_NUMERIC);
		#$statusList = array_slice($statusList, 0, $this->limit, true);
		$this->listSize = count($statusList);
	}

	if ($this->context == "my" || $this->context == "all") {

		#$myList['Van mij'] = 2;
		#$myList['Met mijn reactie'] = 1;
		$myList['act'] = 2;
		$myList['react'] = 1;

		arsort($myList, SORT_NUMERIC);
		#$myList = array_slice($myList, 0, $this->limit, true);
		$this->listSize = count($myList);
	}

	# network
	if ($this->context == "network" || $this->context == "all") {
		$networkContext = SearchWordGetWords('network', $this->start);

		foreach (array_keys($networkContext) as $knKey) {
			$networkList[$networkContext[$knKey]['Word']] = $networkContext[$knKey]['Count'];
		}

		arsort($networkList, SORT_NUMERIC);
		$networkList = array_slice($networkList, 0, $this->limit, true);
		$this->listSize = count($networkList);
	}

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = $this->listSize;

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['suggestList'] = array();
	$this->ses['response']['param']['indexList'] = array();

	# knowledge
	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;

	# hobby
	$this->ses['response']['param']['hobbyList'] = $hobbyList;

	# personal
	$this->ses['response']['param']['businessunitList'] = $businessunitList;
	$this->ses['response']['param']['workplaceList'] = $workplaceList;

	# status & my
	$this->ses['response']['param']['statusList'] = $statusList;
	$this->ses['response']['param']['myList'] = $myList;

	# network
	$this->ses['response']['param']['networkList'] = $networkList;
    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
