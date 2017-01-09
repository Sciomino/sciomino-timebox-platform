<?

class dataUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->data = array();
	$datas = array();

	$where = "";

	#
	# get params
	# - data/ID/update
	# - data/update?data[ID1]&data[ID2]
	#
	$this->dataId = $this->ses['request']['REST']['param'];
	$this->dataIdList = $this->ses['request']['param']['data'];

	# references
	# $this->userId = $this->ses['request']['param']['userId'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->data['name'] = $this->ses['request']['param']['name']; }
	if (isset($this->ses['request']['param']['value'])) { $this->data['value'] = $this->ses['request']['param']['value']; }

	#
	# create data list
	#
        if (isset ($this->dataId)) {
                $datas[] = $this->dataId;
        }

        if (isset ($this->dataIdList)) {
                foreach (array_keys($this->dataIdList) as $aKey) {
                        $datas[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($datas) > 0)) {
	
		$this->status = UserDataUpdate($datas, $this->data);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['datas'] = $datas;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
