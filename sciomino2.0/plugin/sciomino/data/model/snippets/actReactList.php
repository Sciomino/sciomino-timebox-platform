<?

class actReactList extends control {

    function Run() {

        global $XCOW_B;
	//
	// who?
	//
        $this->id = $this->ses['id'];

	// param
	$this->act = $this->ses['request']['param']['act'];
	$this->mode = $this->ses['request']['param']['mode'];
	if (! isset($this->mode)) { $this->mode = 'list'; }

	// init
	$story = 0;
	$actList = array();
	$userList = array();

	//
	// get result information from FIRST 10 id's using XmlHttpRequest
	// - get information from next 10 results when 'more' button is pushed
	//
	$actString = "parent=".$this->act;
	$actString .= "&order=time&direction=asc";
	$actList = AnswersApiListActWithQuery($actString);

	$this->ses['response']['param']['actList'] = $actList;

	foreach ($actList as $actKey => $actVal) {
		if ($actVal['story'] == 1) {
			$story = $actKey;
		}
	}
	$this->ses['response']['param']['story'] = $story;

	//
	// analyse act list
	// - get userinfo
	$userString = "";
	foreach ($actList as $actKey => $actValue) {
		$userString .= "user[".UserApiGetUserFromReference($actValue['Reference'])."]&";
	}
	$userString = rtrim($userString, "&");
	$userString .= "&format=short";
	$userList = UserApiListUserWithQuery($userString);

	$this->ses['response']['param']['userList'] = $userList;

	// end
	$this->ses['response']['param']['userRef'] = $this->id;
	$this->ses['response']['param']['act'] = $this->act;
	$this->ses['response']['param']['mode'] = $this->mode;
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
     }

}

?>
