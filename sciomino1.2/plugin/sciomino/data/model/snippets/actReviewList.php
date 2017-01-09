<?

class actReviewList extends control {

    function Run() {

        global $XCOW_B;

		// who?
        $this->id = $this->ses['id'];

		// params
		$this->act = makeIntString($this->ses['request']['param']['act']);
		$this->parent = makeIntString($this->ses['request']['param']['parent']);

		//
		// get review list (via act)
		// - could make an AnswersApiListReview call in ApiClient...
		//
		if ($this->parent == "") {
			$actList = AnswersApiListActById($this->act);
		}
		else{
			// try to get list from comment
			$actList = AnswersApiListActWithQuery("parent=".$this->parent."&act[$this->act]");
		}
		$this->ses['response']['param']['reviewList'] = $actList[$this->act]['Review'];

		//
		// analyse review list
		// - get userinfo (long for division & role)
		$userString = "";
		foreach ($actList[$this->act]['Review'] as $reviewKey => $reviewValue) {
			$userString .= "user[".UserApiGetUserFromReference($reviewValue['Reference'])."]&";
		}
		$userString = rtrim($userString, "&");
		$userString .= "&format=long";
		$userList = UserApiListUserWithQuery($userString);
		$this->ses['response']['param']['userList'] = $userList;

		// end
		$this->ses['response']['param']['userRef'] = $this->id;
		$this->ses['response']['param']['act'] = $this->act;
		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
	}

}

?>
