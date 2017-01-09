<?

class experience extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->experience = $this->ses['request']['param']['e'];
	$this->title = $this->ses['request']['param']['title'];
	$this->alternative = $this->ses['request']['param']['alternative'];
	$this->like = $this->ses['request']['param']['like'];
	$this->has = $this->ses['request']['param']['has'];

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 20;}

	// init
	// set $max to show at once
	$max = ($this->limit * 2);
	$searchList = array();
	$userList = array();

	$detailTitle = "";
	$detailAlternative = "";
	$detailLike = "";
	$detailHas = "";

	// search
	$experienceKey = key($this->experience);
	$experienceValue = current($this->experience);

	$query = "&e[".urlencode($experienceKey)."][".urlencode($experienceValue)."]";
	if (isset($this->title)) {
		$detailTitle = $this->title;
	}
	if (isset($this->alternative)) {
		$detailAlternative = $this->alternative;
	}
	if (isset($this->like)) {
		$detailLike = $this->like;
	}
	if (isset($this->has)) {
		$detailHas = $this->has;
	}
	# double urlencode to escape the , in the input...
	$query .= "=".urlencode(urlencode($detailTitle)).",".urlencode(urlencode($detailAlternative)).",".urlencode($detailLike).",".urlencode($detailHas);
	$searchList = UserApiListSearchWithQuery("userId=".$this->userId."&detail=none&order=lastname".$query);

	// detail
	$experience = array();
	$experience['detail'] = 'experience';
	$experience['experienceDetail'] = $experienceKey.",".$experienceValue;

	if ($detailTitle != '') {
		$experience['experienceTitleDetail'] = $detailTitle;
	}
	if ($detailAlternative != '') {
		$experience['experienceAlternativeDetail'] = $detailAlternative;
	}
	if ($detailLike != '') {
		$experience['experienceLikeDetail'] = $detailLike;
	}
	if ($detailHas != '') {
		$experience['experienceHasDetail'] = $detailHas;
	}
	$detailList = UserApiDetailSearchExperience($experience, "userId=".$this->userId."&e[".urlencode($experienceKey)."][".urlencode($experienceValue)."]");
	// don't sort on count.
	ksort($detailList['like']);
	ksort($detailList['has']);

	$userCount = 0;
	// count with like, to count experiences and not users.	
	// was: $userCount = count($searchList['user']);
	foreach($detailList['like'] as $like => $count) {
		$userCount = $userCount + $count;
	}

	$metoo = 0;
	if (in_array($this->userId, $searchList['user'])) {
		$metoo = 1;
	}

	// info
	$this->ses['response']['param']['userCount'] = $userCount;
	$this->ses['response']['param']['likes'] = $detailList['like'];
	$this->ses['response']['param']['showMetoo'] = $metoo;
	$this->ses['response']['param']['me'] = $this->userId;

	$this->ses['response']['param']['type'] = $experienceKey;
	$this->ses['response']['param']['experience'] = $experienceValue;
	$this->ses['response']['param']['experienceTitle'] = $detailTitle;
	$this->ses['response']['param']['experienceAlternative'] = $detailAlternative;
	$this->ses['response']['param']['experienceLike'] = $detailLike;
	$this->ses['response']['param']['experienceHas'] = $detailHas;
	$this->ses['response']['param']['experienceDetail'] = $detailList;
	$this->ses['response']['param']['experiencePublisher'] = "";

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

	// results
	$publisher = "";
	$numberOfUsers = count($searchList['user']);
	if ($numberOfUsers > $this->offset) {
		$userString = "";
		if ($numberOfUsers < $max) {
			$this->limit = $numberOfUsers + 1;
		}
		$userLimit = array_slice($searchList['user'], $this->offset, $this->limit);
		$userQuery = "";
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
			$publisher = $userExperienceInfo[$userId]['publisher'];

			$userString .= "user[".$userId."]&";
		}
		$userString = rtrim($userString, "&");
		// append same order
		$userString .= "&order=lastname";
		$userList = UserApiListUserWithQuery($userString);

	}

	# prefill publisher field with last publisher info
	$this->ses['response']['param']['experiencePublisher'] = $publisher;

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList['user'], 0, 100));

	$this->ses['response']['param']['userList'] = $userList;
	$this->ses['response']['param']['UserExperienceInfo'] = $userExperienceInfo;

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	//
	// best/worst
	//

	// get best/worst experiences from stats
	// ... but, not for product detail experiences... TODO....
	$statsList = array();
	if ($detailTitle == '') {

		$statsList = current(UserApiListStatsWithQuery("mode=score"));
		$statsListKey = "Score_".$experienceKey."XSubject";
		$minVal = 999999;
		$minString = "";
		$maxVal = -999999;
		$maxString = "";
		if (array_key_exists($statsListKey, $statsList)) {
			foreach ($statsList[$statsListKey] as $key => $val) {
				if (strstr($val['label'], $experienceValue) == $val['label']) {
					if ($minVal > $val['count']) {
						$minVal = $val['count'];
						$minString = $val['label'];
					}
					if ($maxVal < $val['count']) {
						$maxVal = $val['count'];
						$maxString = $val['label'];
					}
				} 
			}
		}

		list($bestSubject, $bestTitle) = explode("||", $maxString);
		list($worstSubject, $worstTitle) = explode("||", $minString);

		if ($minString != $maxString) {

			// get best/worst likes from experiences detail
			$bestList = array();
			$bestExperience = array();
			$bestExperience['detail'] = 'experience';
			$bestExperience['experienceDetail'] = $experienceKey.",".$bestSubject;
			$bestExperience['experienceTitleDetail'] = $bestTitle;
			$bestList = UserApiDetailSearchExperience($bestExperience, "userId=".$this->userId."&e[".urlencode($experienceKey)."][".urlencode($bestSubject)."]");

			$bestCount = 0;
			foreach($bestList['like'] as $like => $count) {
				$bestCount = $bestCount + $count;
			}

			$worstList = array();
			$worstExperience = array();
			$worstExperience['detail'] = 'experience';
			$worstExperience['experienceDetail'] = $experienceKey.",".$worstSubject;
			$worstExperience['experienceTitleDetail'] = $worstTitle;
			$worstList = UserApiDetailSearchExperience($worstExperience, "userId=".$this->userId."&e[".urlencode($experienceKey)."][".urlencode($worstSubject)."]");

			$worstCount = 0;
			foreach($worstList['like'] as $like => $count) {
				$worstCount = $worstCount + $count;
			}

			$this->ses['response']['param']['bestWorst'] = 1;
			$this->ses['response']['param']['bestSubject'] = $bestSubject;
			$this->ses['response']['param']['bestTitle'] = $bestTitle;
			$this->ses['response']['param']['bestList'] = $bestList;
			$this->ses['response']['param']['bestCount'] = $bestCount;
			$this->ses['response']['param']['worstSubject'] = $worstSubject;
			$this->ses['response']['param']['worstTitle'] = $worstTitle;
			$this->ses['response']['param']['worstList'] = $worstList;
			$this->ses['response']['param']['worstCount'] = $worstCount;

		}
		else {
			// only one experience, nothing2compare
			$this->ses['response']['param']['bestWorst'] = 0;
		}
	}
	else {
		// no stats for product details...(yet)
		$this->ses['response']['param']['bestWorst'] = 0;
	}

     }

}

?>
