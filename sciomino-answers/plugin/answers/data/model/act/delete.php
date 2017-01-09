<?

class actDelete extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$acts = array();

	#
	# get params
	# - act/ID/delete
	# - act/delete?act[ID1]&act[ID2]
	#
	$this->actId = $this->ses['request']['REST']['param'];
	$this->actIdList = $this->ses['request']['param']['act'];

	#
	# create connect list
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
	# DELETE
	#
	if (! $this->status) {

		$this->status = ActDelete($acts);

		foreach ($acts as $actId) {
			setQueueEntry($actId, $actId);
		}

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
