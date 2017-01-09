<?

class widgetsView extends control {

    function Run() {

        global $XCOW_B;

		$this->wid = $this->ses['request']['param']['wid'];
		$this->width = $this->ses['request']['param']['width'];
    	if (! isset($this->width)) {$this->width = 400;}
    
		// get detailed list of users in widget based on search parameters
		// - typeList is already defined in the config of the widget
		// - list is not allowed because it's personal
		// - free search with name and query disabled for performance reasons
		
		// $this->name = $this->ses['request']['param']['n'];
		// $this->query = $this->ses['request']['param']['q'];
		$this->knowledge = $this->ses['request']['param']['k'];
		if (! isset($this->knowledge)) { $this->knowledge = array(); }
		$this->experience = $this->ses['request']['param']['e'];
		if (! isset($this->experience)) { $this->experience = array(); }
		$this->hobby = $this->ses['request']['param']['h'];
		if (! isset($this->hobby)) { $this->hobby = array(); }
		$this->tag = $this->ses['request']['param']['t'];
		if (! isset($this->tag)) { $this->tag = array(); }
		$this->personal = $this->ses['request']['param']['p'];
		if (! isset($this->personal)) { $this->personal = array(); }

		$focus = "";
		//if ($this->name != '') {
		//	$focus .= "&n=".urlencode($this->name);
		//}
		//if ($this->query != '') {
		//	$focus .= "&q=".urlencode($this->query);
		//}
		foreach ($this->knowledge as $key => $val) {
			if ($val != '') {
				$focus .= "&k[".urlencode($key)."]=".urlencode($val);
			}
			else {
				$focus .= "&k[".urlencode($key)."]";
			}
		}
		foreach ($this->experience as $key => $val) {
			foreach ($val as $subkey => $subval) {
				if ($subval != '') {
					$focus .= "&e[".urlencode($key)."][".urlencode($subkey)."]=".urlencode($subval);
				}
				else {
					$focus .= "&e[".urlencode($key)."][".urlencode($subkey)."]";
				}
			}
		}
		foreach ($this->hobby as $key => $dummy) {
			$focus .= "&h[".urlencode($key)."]";
		}
		foreach ($this->tag as $key => $dummy) {
			$focus .= "&t[".urlencode($key)."]";
		}
		foreach ($this->personal as $key => $val) {
			$focus .= "&p[".urlencode($key)."]=".urlencode($val);
		}

		// acts are searched with ActList, not with the index...
		$focusActList = "";
		foreach ($this->knowledge as $key => $dummy) {
			$focusActList .= "&profile[knowledgefield][field][".urlencode($key)."]";
		}
		foreach ($this->hobby as $key => $dummy) {
			$focusActList .= "&profile[hobbyfield][field][".urlencode($key)."]";
		}
		
		// buttons have single values
		$knowledgeButton = current(array_keys($this->knowledge));
		$tagButton = current(array_keys($this->tag));
 
        // get widget info
        $widget = WidgetGetWidgetWithWID($this->wid);
		if (count($widget) != 0) {
			// widget return list of acts
			if ($widget['name'] == "act") {
				$this->act($widget['network'], $focusActList);
	      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/widgets/view-'.$widget['name'].'.php';
			}
			// widget return list of users
			elseif ($widget['name'] == "user") {
				$this->user($widget['network'], $focus);
	      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/widgets/view-'.$widget['name'].'.php';
			}
			// widget return list of knowledge
			elseif ($widget['name'] == "knowledge") {
				$this->knowledge($widget['network'], $focus);
	      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/widgets/view-'.$widget['name'].'.php';
			}
			// widget return knowledge button
			elseif ($widget['name'] == "knowledgeButton") {
				$this->knowledgeButton($widget['language'], $widget['network'], $knowledgeButton);
	      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/widgets/view-button.php';
			}
			// widget return tag button
			elseif ($widget['name'] == "tagButton") {
				$this->tagButton($widget['language'], $widget['network'], $tagButton);
	      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/widgets/view-button.php';
			}
			// widget return a private list of users
			elseif ($widget['name'] == "private") {
				$this->privateUser($widget['owner'], $widget['key'], $widget['network'], $focus);
	      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/widgets/view-user.php';
			}
			else {
				// return empty widget by default
			}
		}
		else {
			// return empty widget by default
		}
        
   		$this->ses['response']['param']['wid'] = $this->wid;
   		$this->ses['response']['param']['width'] = $this->width;
   		$this->ses['response']['param']['language'] = $widget['language'];
   		$this->ses['response']['param']['focus'] = $focus;
   		$this->ses['response']['param']['actFocus'] = $focus;
   		if ($widget['network'] != "") {
			$this->ses['response']['param']['focus'] = "tl[public]=".urlencode($widget['network'])."&".$focus;
			$this->ses['response']['param']['actFocus'] = "net[".urlencode($widget['network'])."]&".$focus;
		}

     }

	function act ($network, $focus) {
		// get act list
		// - optional: filter by network
		$actList = array();
		$networkFilter="";
		if ($network != "") {
			$networkFilter = "&annotation[network]=".urlencode($network);
		}
		
       	$actList = AnswersApiListActWithQuery("parent=0&limit=10&order=time&direction=desc".$focus.$networkFilter);

		# get matching users
		$userString = "";
		$userList = array();
		$refSeen = array();
		if (count($actList) > 0) {
			foreach ($actList as $actKey => $actValue) {
				if (! in_array($actValue['Reference'], $refSeen)) {
					$refSeen[] = $actValue['Reference'];
					$userString .= "refX[".$actValue['Reference']."]&";
				}
			}
			$userString = rtrim($userString, "&");
			$userString .= "&format=short";
			$userList = UserApiListUserWithQuery($userString);
		}

		$this->ses['response']['param']['actList'] = $actList;
		$this->ses['response']['param']['userList'] = $userList;
	}

	function user ($network, $focus) {
		// get search list
		// - optional: filter by network
		$searchList = array();
		$networkFilter="";
		if ($network != "") {
			$networkFilter = "&tl[public]=".urlencode($network);
		}

		$searchList = UserApiListSearchWithQuery("limit=10&order=lastname&direction=desc&".$focus.$networkFilter);

		# get matching users
		$userString = "";
		$userList = array();
		if (count($searchList['user']) > 0) {
			foreach ($searchList['user'] as $userId) {
				$userString .= "user[".$userId."]&";
			}
			$userString = rtrim($userString, "&");
			$userString .= "&format=short";
			$userList = UserApiListUserWithQuery($userString);
		}

		$this->ses['response']['param']['userList'] = $userList;
	}

	function knowledge ($network, $focus) {
		// get search list
		// - optional: filter by network
		$searchList = array();
		$networkFilter="";
		if ($network != "") {
			$networkFilter = "&tl[public]=".urlencode($network);
		}

		#$searchList = UserApiListSearchWithQuery("detail=".$detailUrl."Only&userId=".$this->userId."&".$focus);
		$searchList = UserApiListSearchWithQuery("detail=knowledgeOnly&limit=100&order=lastname&direction=desc&".$focus.$networkFilter);
		
		$knowledgeList = array();
		$knowledgeList = $searchList["knowledge"];
		
		//get 20 random entries
		$kKeys = array_keys($knowledgeList);
		shuffle($kKeys);
		$knowledgeListShuffled = array();
		foreach ($kKeys as $key) {
			$knowledgeListShuffled[$key] = $knowledgeList[$key];
		}
		$knowledgeListShuffled = array_slice($knowledgeListShuffled, 0, 20);
		uksort($knowledgeListShuffled, "strnatcasecmp");

		$this->ses['response']['param']['knowledgeList'] = $knowledgeListShuffled;
	}

	function knowledgeButton ($language, $network, $knowledgeButton) {
		// get search list
		// - optional: filter by network
		$searchList = array();
		$networkFilter="";
		if ($network != "") {
			$networkFilter = "&tl[public]=".urlencode($network);
		}

		//detail is dummy, not needed for output
		$searchList = UserApiListSearchWithQuery("detail=knowledgeOnly&order=lastname&direction=desc&k[".urlencode($knowledgeButton)."]".$networkFilter);
		
		$userCount = count($searchList['user']);
		$userText = "Show all users with";
		if ($language == "nl") {
			$userText = "Toon alle users met";
		}
		$this->ses['response']['param']['buttonText'] = $userText." ".$knowledgeButton." (".$userCount.")";
		$this->ses['response']['param']['buttonUrl'] = "/browse/knowledge?k=".urlencode($knowledgeButton);
	}

	function tagButton ($language, $network, $tagButton) {
		// get search list
		// - optional: filter by network
		$searchList = array();
		$networkFilter="";
		if ($network != "") {
			$networkFilter = "&tl[public]=".urlencode($network);
		}

		//detail is dummy, not needed for output
		$searchList = UserApiListSearchWithQuery("detail=tagOnly&order=lastname&direction=desc&t[".urlencode($tagButton)."]".$networkFilter);
		
		$userCount = count($searchList['user']);
		$userText = "Show who's going to";
		if ($language == "nl") {
			$userText = "Toon wie deelneemt aan";
		}
		$this->ses['response']['param']['buttonText'] = $userText." ".$tagButton." (".$userCount.")";
		$this->ses['response']['param']['buttonUrl'] = "/browse/tag?t=".urlencode($tagButton);
	}

	function privateUser ($owner, $key, $network, $focus) {
		// get search list
		// - optional: filter by network
		$searchList = array();
		$networkFilter="";
		if ($network != "") {
			$networkFilter = "&tl[public]=".urlencode($network);
		}

		$searchList = UserApiListSearchWithQuery("limit=10&order=lastname&direction=desc&l[".$key."]&userId=".$owner."&".$focus.$networkFilter);

		# get matching users
		$userString = "";
		$userList = array();
		if (count($searchList['user']) > 0) {
			foreach ($searchList['user'] as $userId) {
				$userString .= "user[".$userId."]&";
			}
			$userString = rtrim($userString, "&");
			$userString .= "&format=short";
			$userList = UserApiListUserWithQuery($userString);
		}

		$this->ses['response']['param']['userList'] = $userList;
	}
	
}

?>

