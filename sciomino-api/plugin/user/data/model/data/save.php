<?

class dataSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->data = array();
	$dataId = 0;

	#
	# get params
	#
	$this->data['name'] = $this->ses['request']['param']['name'];
	$this->data['value'] = $this->ses['request']['param']['value'];

	# reference
        $this->userId = $this->ses['request']['param']['userId'];

	#
	# NEW SETTINGS
	#
	if (! $this->status) {

		$dataId = UserDataInsert($this->data, $this->userId);

        	if ($dataId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['dataId'] = $dataId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
