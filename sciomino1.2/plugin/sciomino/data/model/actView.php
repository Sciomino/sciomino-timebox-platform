<?

class actView extends control {

    function Run() {

        global $XCOW_B;
	//
	// who?
	//
        $this->id = $this->ses['id'];

	// param
	$this->act = $this->ses['request']['param']['act'];

	// init
	$actList = array();
	$act = array();
	$userList = array();
	$user = array();
	$reactList = array();
	$story = 0;
	$photo = '';
	$like = 0;

	// act info
	$actList = AnswersApiListActById($this->act);

	// don't allow empty page
	if ( count($actList) == 0 ) {
		$this->ses['response']['redirect'] = "/error404";
	}

	$act = $actList[$this->act];

	//
	// analyse act
	// - get userinfo
	// - get reactions count
	// - has story?
	$actRef = $act['Reference'];
	# too slow
	#$actUser = UserApiGetUserFromReference($actRef);
	#$userString = "user[".$actUser."]";
	$userString = "refX[".$actRef."]";

	$reactString = "parent=".$this->act."&order=time&direction=asc";
	$reactList = AnswersApiListActWithQuery($reactString);

	$refSeen = array();
	foreach ($reactList as $reactKey => $reactVal) {
		// get all user info
		#too slow
		#$userString .= "&user[".UserApiGetUserFromReference($reactVal['Reference'])."]";
		if (! in_array($reactVal['Reference'], $refSeen)) {
			$refSeen[] = $reactVal['Reference'];
			$userString .= "&refX[".$reactVal['Reference']."]";
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

	$act['Story'] = $story;
	$act['Photo'] = $photo;
	$act['Like'] = $like;
	$act['Reactions'] = count($reactList);

	// get user list of act & reacts together
	$userString .= "&format=short";
	$userList = UserApiListUserWithQuery($userString);
	$user = $userList[get_id_from_multi_array($userList, 'Reference', $actRef)];

	// content
	$this->ses['response']['param']['userRef'] = $this->id;
	$this->ses['response']['param']['allRefs'] = $refSeen;
	$this->ses['response']['param']['act'] = $act;
	$this->ses['response']['param']['user'] = $user;
	$this->ses['response']['param']['reactList'] = $reactList;
	$this->ses['response']['param']['userList'] = $userList;

	// end
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
     }

}

?>
