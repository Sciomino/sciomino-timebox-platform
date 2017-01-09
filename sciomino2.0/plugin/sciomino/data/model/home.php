<?

class webHome extends control {

    function Run() {

        global $XCOW_B;
        
		// who?
		$this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		# get activity list for all updates	
		# fetch 100 to create 10...
		$this->offset = 0;
		$this->limit = 100;
		$this->max = 10;
		# could add availability here later...
		$validTitles = array('motd', 'knowledge', 'save_user_profile_knowledgefield', 'save_user_profile_hobbyfield', 'save_user_profile_tag');
		$nonValidTitles = array('save_user', 'save_user_profile_availability');
		$query = "";
		$first = 1;
		foreach ($nonValidTitles as $vt) {
			if (! $first) { $query .= "&"; }
			else { $first = 0; }
			$query .= "tl[]=$vt";
		}
		$activityList = array();
		$activityList = UserApiListActivityWithQuery($query."&title_match=not&order=date&direction=desc&offset=".$this->offset."&limit=".$this->limit);

		$countAll = 0;
		$countDisplay = 0;
		$prevTitle = "";
		$prevUser = 0;
		foreach ($activityList as $key => $activity) {
			$countAll++;
			
			// skip empty activities
			if ($activity['Title'] == "" || $activity['Description'] == "") {
				$activityList[$key]['Description'] = "";
				continue;
			}

			// skip non valid titles
			if (!in_array($activity['Title'], $validTitles)) {
				$activityList[$key]['Description'] = "";
				continue;
			}
			
			// don't repeat the same activity from one user 
			if ($activity['Title'] == $prevTitle && $activity['UserId'] == $prevUser) {
				$activityList[$key]['Description'] = "";
				continue;
			}			
			
			// assume a new update is found
			$updatePrev = 1;
			// users must be activated
			if ($activity['Title'] == "save_user") {
				$userArray = UserApiListUserWithQuery("mode=active&accessId=4&accessId_match=not&user[".$activity['UserId']."]");
				if (count($userArray) == 0) {
					# user not activated, so don't show 
					$activityList[$key]['Description'] = "";
					$updatePrev = 0;
				}
			}
			// knowledge/hobby/tags still need to exist
			if ($activity['Title'] == "save_user_profile_knowledgefield" || $activity['Title'] == "save_user_profile_hobbyfield" || $activity['Title'] == "save_user_profile_tag") {
				$profileArray = UserApiListProfileById("user", $activity['UserId'], $activity['Description']);
				if (count($profileArray) == 0) {
					# activity not found 
					$activityList[$key]['Description'] = "";
					$updatePrev = 0;
				}
				else {
					$profileArray = current($profileArray);
					if ($activity['Title'] == "save_user_profile_tag") {
						$activityList[$key]['Description'] = $profileArray['name'];
					}
					else {
						$activityList[$key]['Description'] = $profileArray['field'];
					}
				}
			}
			if ($updatePrev) {
				$prevTitle = $activity['Title'];
				$prevUser = $activity['UserId'];
				$countDisplay++;
			}
			
			// end here
			if ($countDisplay == $this->max) {
				break;
			}
		}
		$activityList = array_slice($activityList, 0, $countAll);
		
		# get activity list for knowledge wanted	
		$this->offset2 = 0;
		$this->limit2 = 3;
		$activityList2 = array();
		$activityList2 = UserApiListActivityWithQuery("title=knowledge&title_match=exact&order=date&direction=desc&offset=".$this->offset2."&limit=".$this->limit2);

		$thereIsMore = 1;
		if (count($activityList2) < $this->limit2) {
			$thereIsMore = 0;
		}
		
		$this->ses['response']['param']['thereIsMore'] = $thereIsMore;
		$this->ses['response']['param']['newLimit'] = $this->limit2 + 3;
		$this->ses['response']['param']['meUser'] = $this->userId;
		$this->ses['response']['param']['activityList'] = $activityList;
		$this->ses['response']['param']['activityList2'] = $activityList2;

		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
