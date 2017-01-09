<?

class actListPersonal extends control {

    function Run() {

        global $XCOW_B;
	//
	// who?
	//
    $this->id = $this->ses['id'];
    // long is not necessary
	//$this->userInfo = current(UserApiListUserWithQuery("reference=".$this->id, "SC_UserApiListUserWithQuery_".$this->id."_long"));
	// this call is cached by default in UserApiClient
	$this->userInfo = current(UserApiListUserByReference($this->id));

	// param
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 10;}

        $this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) { $this->mode = 'list'; }
	$this->ses['response']['param']['mode'] = $this->mode;

	$this->label = $this->ses['request']['param']['label'];
       	if (! isset($this->label)) {$this->label = "";}
	$this->ses['response']['param']['label'] = $this->label;

	// init
	$query = "";
	$actList = array();
	$act2List = array();
	$userList = array();

	// build query list
	// - get all open acts with knowledgefields the user is expert in
	if (is_array($this->userInfo['knowledgefield'])) {
		$query = "open=1&profile_param=any&limit=".$this->limit."&order=time&direction=desc&reference_match=not&reference=".$this->id;
		foreach ($this->userInfo['knowledgefield'] as $key => $val) {
			// pick the expert level
			if ($val['level'] == "1") {
				$query .= "&profile[knowledgefield][field][".urlencode($val['field'])."]";
			}
		}
		$actList = AnswersApiListActWithQuery($query);
	}

	// add the latest open acts
	$query_limit = $this->limit - count($actList);
	if ($query_limit > 0) {
		$query2 = "open=1&limit=".$query_limit."&order=time&direction=desc&reference_match=not&reference=".$this->id;
		$act2List = AnswersApiListActWithQuery($query2);
		$actList = $actList + $act2List;
	}

	// add closed acts with a story, even from myself
	$query_limit = $this->limit - count($actList);
	$query3 = "";
	if ($query_limit > 0) {
		$searchList = AnswersApiListSearchWithQuery("s[closed_story]&limit=".$query_limit."&order=time&direction=desc");
		if (count($searchList['act']) > 0) {
			foreach ($searchList['act'] as $actId) {
				$query3 .= "act[".$actId."]&";
			}
			$query3 = rtrim($query3, "&");
			// append same order
			$query3 .= "&order=time&direction=desc&limit=".$query_limit;	
			//include your own stories, these might be important
			//$query3 .= "&reference_match=not&reference=".$this->id;
			
			$act3List = AnswersApiListActWithQuery($query3);
			$actList = $actList + $act3List;
		}
	}

	//
	// analyse act list
	// - get userinfo
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

        # TODO: mogelijk performance issues als ik voor iedere act alle reactions ophaal, alleen voor de story...
        foreach ($actList as $actKey => $actValue) {
                $story = 0;
                $reactString = "parent=".$actKey;
                $reactString .= "&order=time&direction=desc";
                $reactList = AnswersApiListActWithQuery($reactString);

                foreach ($reactList as $reactKey => $reactVal) {
                        if ($reactVal['story'] == 1) {
                                $story = $reactKey;
                                break;
                        }
                }

                $actList[$actKey]['Story'] = $story;
        }

	// more
	$this->ses['response']['param']['actList'] = $actList;
	$this->ses['response']['param']['userList'] = $userList;

	// end
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
     }

}

?>
