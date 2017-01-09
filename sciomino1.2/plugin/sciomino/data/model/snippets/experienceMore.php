<?

class experienceMore extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// param
	$this->experience = $this->ses['request']['param']['e'];
	$this->title = $this->ses['request']['param']['title'];
	$this->alternative = $this->ses['request']['param']['alternative'];
        $this->searchString = $this->ses['request']['param']['searchString'];

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 20;}

	// init
	$searchList = array();
	$userList = array();

	$detailTitle = "";
	$detailAlternative = "";

	// search
	$experienceKey = key($this->experience);
	$experienceValue = current($this->experience);

	if (isset($this->title)) {
		$detailTitle = $this->title;
	}
	if (isset($this->alternative)) {
		$detailAlternative = $this->alternative;
	}

	$searchList = explode(',', $this->searchString);

	// results
	if (count($searchList) > $this->offset) {
		$userString = "";
		$userQuery = "";
		$userLimit = array_slice($searchList, $this->offset, $this->limit);
		foreach ($userLimit as $userId) {
			// TODO: too slow!!! put this in user call!
			$userQuery = "name=".$experienceKey;
			if ($detailTitle != '') {
				$userQuery .= "&annotation[title]=".urlencode($detailTitle);
			}
			if ($detailAlternative != '') {
				$userQuery .= "&annotation[alternative]=".urlencode($detailAlternative);
			}
			$infoList = UserApiListSectionWithQuery('experience', $userId, $userQuery);

			$userExperienceInfo[$userId] = current($infoList);

			$userString .= "user[".$userId."]&";
		}
		$userString = rtrim($userString, "&");
		// append same order
		$userString .= "&order=lastname";
		$userList = UserApiListUserWithQuery($userString);


	}

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList, 0, 100));

	$this->ses['response']['param']['userList'] = $userList;
	$this->ses['response']['param']['UserExperienceInfo'] = $userExperienceInfo;

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	$this->ses['response']['param']['type'] = $experienceKey;
	$this->ses['response']['param']['experience'] = $experienceValue;
	$this->ses['response']['param']['experienceTitle'] = $detailTitle;
	$this->ses['response']['param']['experienceAlternative'] = $detailAlternative;

	$this->ses['response']['param']['me'] = $this->userId;

     }

}

?>
