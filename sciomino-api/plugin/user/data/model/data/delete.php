<?

class dataDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$datas = array();

	#
	# get params
	# - data/ID/delete
	# - data/delete?data[ID1]&data[ID2]
	#
	$this->dataId = $this->ses['request']['REST']['param'];
	$this->dataIdList = $this->ses['request']['param']['data'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserDataDelete($datas);

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
