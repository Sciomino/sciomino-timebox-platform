<?

class actSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->act = array();
	$actId = 0;

	#
	# get params
	#
	# defaults:
	# - no expiration (= '2147483647', the end of the universe as we know it :-)
	# - is active (= 1)
	# - no parent (= 0)
	$this->act['description'] = $this->ses['request']['param']['description'];
	$this->act['expiration'] = $this->ses['request']['param']['expiration'];
        if (! isset($this->act['expiration'])) {$this->act['expiration'] = 2147483647;}
	$this->act['active'] = $this->ses['request']['param']['active'];
        if (! isset($this->act['active'])) {$this->act['active'] = 1;}
	$this->act['parent'] = $this->ses['request']['param']['parent'];
        if (! isset($this->act['parent'])) {$this->act['parent'] = 0;}

	$this->reference = $this->ses['request']['param']['reference'];

        #
        # check reference
        #
        if (! isset($this->reference) ) {
		$this->reference = "";
        }

	#
	# NEW
	#
	if (! $this->status) {

		$actId = ActInsert($this->act, $this->reference);

        	if ($actId == 0) {
 			$this->status = "500 Internal Error";
        	}
		else {
			setQueueEntry($actId, $actId);
		}

        }

	#
	# Content
	#
        $this->ses['response']['param']['actId'] = $actId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
