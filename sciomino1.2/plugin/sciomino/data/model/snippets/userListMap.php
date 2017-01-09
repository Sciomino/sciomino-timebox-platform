<?

class userListMap extends control {

    function Run() {

        global $XCOW_B;

	// params
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

        $this->detail = $this->ses['request']['param']['detail'];
        if (! isset($this->detail)) {$this->detail = "workplace";}
	$this->view = $this->ses['request']['param']['view'];
        if (! isset($this->view)) {$this->view = "map";}

	// focus
	$focus = "";
	if ($this->name != '') {
		$focus .= "&n=".urlencode($this->name);
	}
	if ($this->query != '') {
		$focus .= "q=".urlencode($this->query);
	}
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
	foreach ($this->list as $key => $dummy) {
		$focus .= "&l[".urlencode($key)."]";
	}
	foreach ($this->typeList as $key => $val) {
		$focus .= "&tl[".urlencode($key)."]=".urlencode($val);
	}

        // content
	$this->ses['response']['param']['view'] = $this->view;
	$this->ses['response']['param']['detail'] = $this->detail;
	$this->ses['response']['param']['focus'] = $focus;
     }

}

?>
