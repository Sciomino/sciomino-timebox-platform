<?

class connectUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->connect = array();
	$connects = array();

	$where = "";

	#
	# get params
	# - connect/ID/update
	# - connect/update?connect[ID1]&connect[ID2]
	#
	$this->connectId = $this->ses['request']['REST']['param'];
	$this->connectIdList = $this->ses['request']['param']['connect'];

	# connect changes
	if (isset($this->ses['request']['param']['type'])) { $this->connect['type'] = $this->ses['request']['param']['type']; } 
	if (isset($this->ses['request']['param']['name'])) { $this->connect['name'] = $this->ses['request']['param']['name']; }

	#
	# create connect list
	#
        if (isset ($this->connectId)) {
                $connects[] = $this->connectId;
        }

        if (isset ($this->connectIdList)) {
                foreach (array_keys($this->connectIdList) as $aKey) {
                        $connects[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($connects) > 0)) {
	
		$this->status = ConnectUpdate($connects, $this->connect);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['connects'] = $connects;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
