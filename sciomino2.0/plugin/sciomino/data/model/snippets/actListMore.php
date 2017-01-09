<?

class actListMore extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];

	// param
        $this->searchString = $this->ses['request']['param']['searchString'];

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 10;}

	// init
	$searchList = array();
	$actList = array();
	$userList = array();

	// get ore results
	$searchList = explode(',', $this->searchString);

	if (count($searchList) > $this->offset) {
		$actString = "";
		$actLimit = array_slice($searchList, $this->offset, $this->limit);
		foreach ($actLimit as $actId) {
			$actString .= "act[".$actId."]&";
		}
		$actString = rtrim($actString, "&");
		// append same order
		$actString .= "&order=time&direction=desc";
		$actList = AnswersApiListActWithQuery($actString);
	}

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList, 0, 100));

	//
	// analyse act list
	// - get userinfo
	// - get reactions count
	// - has story?
	$userString = "";
	foreach ($actList as $actKey => $actValue) {
		$userString .= "user[".UserApiGetUserFromReference($actValue['Reference'])."]&";
	}
	$userString = rtrim($userString, "&");
	$userString .= "&format=short";
	$userList = UserApiListUserWithQuery($userString);

	$this->ses['response']['param']['userRef'] = $this->id;
	$this->ses['response']['param']['userList'] = $userList;

	# TODO: mogelijk performance issues als ik voor iedere act alle reactions ophaal, alleen om te tellen...
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

	$this->ses['response']['param']['actList'] = $actList;

	// more
	$thereIsMore = 1;
	if (count($actList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

     }

}

?>
