<?

class actUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->act = array();
	$acts = array();

	$where = "";

	#
	# get params
	# - act/ID/update
	# - act/update?act[ID1]&act[ID2]
	#
	$this->actId = $this->ses['request']['REST']['param'];
	$this->actIdList = $this->ses['request']['param']['act'];

	# act changes
	if (isset($this->ses['request']['param']['description'])) { $this->act['description'] = $this->ses['request']['param']['description']; } 
	if (isset($this->ses['request']['param']['expiration'])) { $this->act['expiration'] = $this->ses['request']['param']['expiration']; }
	if (isset($this->ses['request']['param']['active'])) { $this->act['active'] = $this->ses['request']['param']['active']; }

	#
	# create act list
	#
        if (isset ($this->actId)) {
                $acts[] = $this->actId;
        }

        if (isset ($this->actIdList)) {
                foreach (array_keys($this->actIdList) as $aKey) {
                        $acts[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($acts) > 0)) {
	
		$this->status = ActUpdate($acts, $this->act);

		foreach ($acts as $actId) {
			setQueueEntry($actId, $actId);
		}

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['acts'] = $acts;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
