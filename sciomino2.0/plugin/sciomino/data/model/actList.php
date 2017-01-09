<?

class actList extends control {

    function Run() {

        global $XCOW_B;
	//
	// who?
	//
        $this->id = $this->ses['id'];

	// param
	$this->newAct = $this->ses['request']['param']['new'];

	$this->query = $this->ses['request']['param']['q'];
	$this->knowledge = $this->ses['request']['param']['k'];
	if (! isset($this->knowledge)) { $this->knowledge = array(); }
	$this->hobby = $this->ses['request']['param']['h'];
	if (! isset($this->hobby)) { $this->hobby = array(); }
	$this->tag = $this->ses['request']['param']['t'];
	if (! isset($this->tag)) { $this->tag = array(); }
	$this->personal = $this->ses['request']['param']['p'];
	if (! isset($this->personal)) { $this->personal = array(); }
	$this->statusList = $this->ses['request']['param']['s'];
	if (! isset($this->statusList)) { $this->statusList = array(); }
	$this->my = $this->ses['request']['param']['m'];
	if (! isset($this->my)) { $this->my = array(); }
	$this->network = $this->ses['request']['param']['net'];
	if (! isset($this->network)) { $this->network = array(); }

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 10;}

        $this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) { $this->mode = 'list'; }

	$this->label = $this->ses['request']['param']['label'];
       	if (! isset($this->label)) {$this->label = "";}
	$this->ses['response']['param']['label'] = $this->label;
 
	// init
	$searchList = array();
	$actList = array();
	$userList = array();

	//
	// get act id's
	// - select act id's from reverse index
	// - ORDER BY subselect field from field_table
	//
	// get filterlijst
	// - select all filters for act id's
	//
	$focus = "";
	if ($this->query != '') {
		$focus .= "&q=".urlencode($this->query);
	}
	foreach ($this->knowledge as $key => $val) {
		if ($val != '') {
			$focus .= "&k[".urlencode($key)."]=".urlencode($val);
		}
		else {
			$focus .= "&k[".urlencode($key)."]";
		}
	}
	foreach ($this->hobby as $key => $dummy) {
		$focus .= "&h[".urlencode($key)."]";
	}
	foreach ($this->tag as $key => $dummy) {
		$focus .= "&t[".urlencode($key)."]";
	}
	foreach ($this->personal as $key => $val) {
		$focus .= "&p[".urlencode($key)."]=".urlencode($val);
	}
	foreach ($this->statusList as $key => $dummy) {
		$focus .= "&s[".urlencode($key)."]";
	}
	foreach ($this->my as $key => $dummy) {
		$focus .= "&m[".urlencode($key)."]";
	}
	foreach ($this->network as $key => $dummy) {
		$focus .= "&net[".urlencode($key)."]";
	}

	ksort($this->knowledge);
	ksort($this->hobby);
	ksort($this->tag);
	ksort($this->personal);
	ksort($this->statusList);
	ksort($this->my);
	ksort($this->network);
	
	$searchList = AnswersApiListSearchWithQuery("reference=".$this->id."&order=time&direction=desc&".$focus);

	//prepend the newAct
	if (isset($this->newAct)) {
		array_unshift($searchList['act'], $this->newAct);
	}
	//don't show these status options.
	//unset($searchList['status']['relevant']);
	unset($searchList['status']['closed_story']);

	$this->ses['response']['param']['userRef'] = $this->id;

	$this->ses['response']['param']['query'] = array();	
	$this->ses['response']['param']['query']['focus'] = $focus;
	$this->ses['response']['param']['query']['words'] = $this->query;
	$this->ses['response']['param']['query']['knowledge'] = $this->knowledge;
	$this->ses['response']['param']['query']['hobby'] = $this->hobby;
	$this->ses['response']['param']['query']['tag'] = $this->tag;
	$this->ses['response']['param']['query']['personal'] = $this->personal;
	$this->ses['response']['param']['query']['statusList'] = $this->statusList;
	$this->ses['response']['param']['query']['my'] = $this->my;
	$this->ses['response']['param']['query']['network'] = $this->network;

	$this->ses['response']['param']['actCount'] = count($searchList['act']);
	$this->ses['response']['param']['suggestList'] = $searchList['suggest'];
	$this->ses['response']['param']['knowledgeList'] = $searchList['knowledge'];
	$this->ses['response']['param']['hobbyList'] = $searchList['hobby'];
	$this->ses['response']['param']['tagList'] = $searchList['tag'];
	$this->ses['response']['param']['businessunitList'] = $searchList['businessunit'];
	$this->ses['response']['param']['workplaceList'] = $searchList['workplace'];
	$this->ses['response']['param']['statusListList'] = $searchList['status'];
	$this->ses['response']['param']['myList'] = $searchList['my'];
	$this->ses['response']['param']['networkList'] = $searchList['network'];

	//
	// get result information from FIRST 10 id's using XmlHttpRequest
	// - get information from next 10 results when 'more' button is pushed
	//
	if (count($searchList['act']) > $this->offset) {
		$actString = "";
		$actLimit = array_slice($searchList['act'], $this->offset, $this->limit);
		foreach ($actLimit as $actId) {
			$actString .= "act[".$actId."]&";
		}
		$actString = rtrim($actString, "&");
		// append same order
		$actString .= "&order=time&direction=desc";
		$actList = AnswersApiListActWithQuery($actString);
	}

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList['act'], 0, 100));

	//
	// analyse act list
	// - get userinfo
	// - get reactions count
	// - has story?
	$userString = "";
	$refSeen = array();
	foreach ($actList as $actKey => $actValue) {
		#too slow
		#$userString .= "user[".UserApiGetUserFromReference($actValue['Reference'])."]&";
		if (! in_array($actValue['Reference'], $refSeen)) {
			$refSeen[] = $actValue['Reference'];
			$userString .= "refX[".$actValue['Reference']."]&";
		}
	}
	$userString = rtrim($userString, "&");
	$userString .= "&format=short";
	$userList = UserApiListUserWithQuery($userString);

	$this->ses['response']['param']['userList'] = $userList;

	# TODO: mogelijk performance issues als ik voor iedere act alle reactions ophaal, alleen om te tellen...
	# if ($this->mode == "list") {
	foreach ($actList as $actKey => $actValue) {
		$story = 0;
		$photo = "";
		$like = 0;
		$reactString = "parent=".$actKey;
		$reactString .= "&order=time&direction=desc";
		$reactList = AnswersApiListActWithQuery($reactString);

		$refSeen = array();
		foreach ($reactList as $reactKey => $reactVal) {
			if (! in_array($reactVal['Reference'], $refSeen)) {
				$refSeen[] = $reactVal['Reference'];
			}
			if ($reactVal['story'] == 1) {
				$story = $reactKey;
				if (isset($reactVal['photo'])) {
					$photo = $reactVal['photo'];
				}
				$like = $reactVal['like'];
				#break;
			}
		}

		$actList[$actKey]['Story'] = $story;
		$actList[$actKey]['Photo'] = $photo;
		$actList[$actKey]['Like'] = $like;
		$actList[$actKey]['Reactions'] = count($reactList);
		$actList[$actKey]['allRefs'] = $refSeen;
	}
	# }

	$this->ses['response']['param']['actList'] = $actList;

	// more
	$thereIsMore = 1;
	if (count($actList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	// mode
	$this->ses['response']['param']['mode'] = $this->mode;

	// end
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
     }

}

?>
