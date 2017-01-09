<?

class connectSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->connect = array();
	$connectId = 0;

	#
	# get params
	#
	$this->connect['type'] = $this->ses['request']['param']['type'];
	$this->connect['name'] = $this->ses['request']['param']['name'];

	$this->reference = $this->ses['request']['param']['reference'];

        #
        # check reference
        #
        if (! isset($this->reference) ) {
		$this->reference = "";
        }

	#
	# NEW USER
	#
	if (! $this->status) {

		$connectId = ConnectInsert($this->connect, $this->reference);

        	if ($connectId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['connectId'] = $connectId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
