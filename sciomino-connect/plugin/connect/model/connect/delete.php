<?

class connectDelete extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$connects = array();

	#
	# get params
	# - connect/ID/delete
	# - connect/delete?connect[ID1]&connect[ID2]
	#
	$this->connectId = $this->ses['request']['REST']['param'];
	$this->connectIdList = $this->ses['request']['param']['connect'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = ConnectDelete($connects);

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
