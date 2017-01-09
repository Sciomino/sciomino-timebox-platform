<?

class userSame extends control {

    function Run() {

    global $XCOW_B;
        
	//
	// who?
	//
    $this->id = $this->ses['id'];

	// param
	$this->user = $this->ses['request']['param']['user'];

	// init
	$userList = array();
	$sameTempList = array();
	$sameUserList = array();

	//
	// go
	//
	if (isset($this->user)) {
		$userList = current(UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user));

		//
		// same user!?
		//
		/* performed niet... en geeft eigenlijk ook geen goed resultaat...
		if (count($userList['knowledgefield']) > 0) {
			$sameQuery = "profile_param=any";
			foreach ($userList['knowledgefield'] as $knowledge) {
				$sameQuery .= "&profile[knowledgefield][field][".urlencode($knowledge['field'])."]";		
			}

			// todo call userList
			$sameQuery = $sameQuery."&limit=5";
			$sameUserList = UserApiListUserWithQuery($sameQuery);
		}
		*/

		if (count($userList['knowledgefield']) > 0) {
			$s = 0;
			foreach ($userList['knowledgefield'] as $knowledge) {
				$sameQuery = "userId=".$this->userId."&detail=none&k[".urlencode($knowledge['field'])."]";
				$searchList = UserApiListSearchWithQuery($sameQuery);
				if ($s==0) {
					$sameTempList = $searchList['user'];
				}
				else {
					# 1. voor ieder kennisveld: voeg alle users samen in 1 array (dus ook dubbele!)
					$sameTempList = array_merge($sameTempList, $searchList['user']);
				}
				$s++;
				# btw: check max 10 kennisvelden...
				if ($s==9) {
					break;
				}
			}
			# 2. tel hoevaak users voorkomen (tel dus de dubbele!)
			$sameTempList = array_count_values($sameTempList);
			# 3. sorteer op deze telling
			arsort($sameTempList, SORT_NUMERIC);
			# 4. pak de bovenste 5
			$sameTempList = array_slice($sameTempList, 0, 5, true);

			if (count($sameTempList) > 0) {
				$userString = "";
				foreach ($sameTempList as $userId => $dummy) {
					$userString .= "user[".$userId."]&";
				}
				$userString = rtrim($userString, "&");
				$userString .= "&order=lastname";
				$sameUserList = UserApiListUserWithQuery($userString);
			}
		}

	}

	// content

	//the user who is viewed
	$this->ses['response']['param']['view'] = $this->user;
	$this->ses['response']['param']['user'] = $userList;
	
	$this->ses['response']['param']['sameUser'] = $sameUserList;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
