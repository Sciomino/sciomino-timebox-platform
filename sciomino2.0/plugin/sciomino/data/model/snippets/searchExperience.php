<?

class searchExperience extends control {

    function Run() {

        global $XCOW_B;
        
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// param
 	$this->type = $this->ses['request']['param']['type'];
 	$this->object = $this->ses['request']['param']['object'];
 	$this->output = $this->ses['request']['param']['output'];
	if (! isset($this->ses['request']['param']['output'])) { $this->output = "all"; }

	$this->name = $this->ses['request']['param']['n'];
	$this->query = $this->ses['request']['param']['q'];
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
	$this->list = $this->ses['request']['param']['l'];
	if (! isset($this->list)) { $this->list = array(); }
	$this->typeList = $this->ses['request']['param']['tl'];
	if (! isset($this->typeList)) { $this->typeList = array(); }

	// focus
	$focus = "";
	$focusStripped = "";
	$detail = "";
	if ($this->name != '') {
		$focus .= "&n=".urlencode($this->name);
		$focusStripped .= "n=".urlencode($this->name);
	}
	if ($this->query != '') {
		$focus .= "q=".urlencode($this->query);
		$focusStripped .= "q=".urlencode($this->query);
	}
	foreach ($this->knowledge as $key => $val) {
		if ($val != '') {
			$focus .= "&k[".urlencode($key)."]=".urlencode($val);
			$focusStripped .= "&k[".urlencode($key)."]=".urlencode($val);
		}
		else {
			$focus .= "&k[".urlencode($key)."]";
			$focusStripped .= "&k[".urlencode($key)."]";
		}
	}
	foreach ($this->experience as $key => $val) {
		foreach ($val as $subkey => $subval) {
			if ($subkey == $this->object) {
				$detail = $subval;
			}
			if ($subval != '') {
				$focus .= "&e[".urlencode($key)."][".urlencode($subkey)."]=".urlencode($subval);
				if ($subkey != $this->object) {
					$focusStripped .= "&e[".urlencode($key)."][".urlencode($subkey)."]=".urlencode($subval);
				}
			}
			else {
				$focus .= "&e[".urlencode($key)."][".urlencode($subkey)."]";
				if ($subkey != $this->object) {
					$focusStripped .= "&e[".urlencode($key)."][".urlencode($subkey)."]";
				}
			}
		}
	}
	foreach ($this->hobby as $key => $dummy) {
		$focus .= "&h[".urlencode($key)."]";
		$focusStripped .= "&h[".urlencode($key)."]";
	}
	foreach ($this->tag as $key => $dummy) {
		$focus .= "&t[".urlencode($key)."]";
		$focusStripped .= "&t[".urlencode($key)."]";
	}
	foreach ($this->personal as $key => $val) {
		$focus .= "&p[".urlencode($key)."]=".urlencode($val);
		$focusStripped .= "&p[".urlencode($key)."]=".urlencode($val);
	}
	foreach ($this->list as $key => $dummy) {
		$focus .= "&l[".urlencode($key)."]";
		$focusStripped .= "&l[".urlencode($key)."]";
	}
	foreach ($this->typeList as $key => $val) {
		$focus .= "&tl[".urlencode($key)."]=".urlencode($val);
		$focusStripped .= "&tl[".urlencode($key)."]=".urlencode($val);
	}

	# build experience data
	$experience = array();
	$experience['detail'] = 'experience';
	$experience['experienceDetail'] = $this->type.",".$this->object;

	list($prevTitle, $prevAlternative, $prevLike, $prevHas) = explode(',', $detail);
	$prevTitle = urldecode($prevTitle);
	$prevAlternative = urldecode($prevAlternative);
	if ($prevTitle != '') {
		$experience['experienceTitleDetail'] = $prevTitle;
	}
	if ($prevAlternative != '') {
		$experience['experienceAlternativeDetail'] = $prevAlternative;
	}
	if ($prevLike != '') {
		$experience['experienceLikeDetail'] = $prevLike;
	}
	if ($prevHas != '') {
		$experience['experienceHasDetail'] = $prevHas;
	}

	# get search list
	$searchList = UserApiDetailSearchExperience($experience, "userId=".$this->userId."&".$focus);

	# content
	$this->ses['response']['param']['titleList'] = $searchList['title'];
	$this->ses['response']['param']['alternativeList'] = $searchList['alternative'];
	$this->ses['response']['param']['likeList'] = $searchList['like'];
	$this->ses['response']['param']['hasList'] = $searchList['has'];

	$this->ses['response']['param']['output'] = $this->output;

	$this->ses['response']['param']['type'] = $this->type;
	$this->ses['response']['param']['experience'] = $this->object;
	$this->ses['response']['param']['focus'] = $focusStripped;

	$this->ses['response']['param']['prevTitle'] = $prevTitle;
	$this->ses['response']['param']['prevAlternative'] = $prevAlternative;
	$this->ses['response']['param']['prevLike'] = $prevLike;
	$this->ses['response']['param']['prevHas'] = $prevHas;

     }

}

?>
