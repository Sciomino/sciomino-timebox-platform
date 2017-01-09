<?

class activityList extends control {

    function Run() {

        global $XCOW_B;

	// who?
	$this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	$this->mode = $this->ses['request']['param']['mode'];
	if (! isset($this->mode)) {$this->mode = "knowledge";}

	$this->offset = $this->ses['request']['param']['offset'];
	if (! isset($this->offset)) {$this->offset = 0;}
	$this->limit = $this->ses['request']['param']['limit'];
	if (! isset($this->limit)) {$this->limit = 5;}

	# get activity list
	$query = "";
	
	if ($this->mode == "knowledge") {
		$query = "title=knowledge&title_match=exact";
	}
	elseif ($this->mode == "message") {
		$query = "title=motd&title_match=exact";
	}
	elseif ($this->mode == "all") {
		$query = "";
		$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/activityListAll.php';
	}
	
	$activityList = array();
	$activityList = UserApiListActivityWithQuery($query."&order=date&direction=desc&offset=".$this->offset."&limit=".$this->limit, "SC_UserApiListActivityWithQuery_".$this->mode."_".$this->offset."_".$this->limit);

	foreach ($activityList as $key => $activity) {
		if ($activity['Title'] == "save_user") {
			$userArray = UserApiListUserWithQuery("mode=active&accessId=4&accessId_match=not&user[".$activity['UserId']."]");
			if (count($userArray) == 0) {
				# user not activated, so don't show 
				$activityList[$key]['Description'] = "";
			}
		}
		if ($activity['Title'] == "save_user_profile_knowledgefield") {
			$profileArray = current(UserApiListProfileById("user", $activity['UserId'], $activity['Description']));
			$activityList[$key]['Description'] = $profileArray['field'];
		}
		if ($activity['Title'] == "save_user_profile_hobbyfield") {
			$profileArray = current(UserApiListProfileById("user", $activity['UserId'], $activity['Description']));
			$activityList[$key]['Description'] = $profileArray['field'];
		}
		if ($activity['Title'] == "save_user_profile_tag") {
			$profileArray = current(UserApiListProfileById("user", $activity['UserId'], $activity['Description']));
			$activityList[$key]['Description'] = $profileArray['name'];
		}
	}

	$thereIsMore = 1;
	if (count($activityList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;
	$this->ses['response']['param']['newLimit'] = $this->limit + 10;

	$this->ses['response']['param']['meUser'] = $this->userId;

	$this->ses['response']['param']['activityList'] = $activityList;

     }

}

?>
