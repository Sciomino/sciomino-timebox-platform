<?

class accessGroupDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$accessGroups = array();

	#
	# get params
	# - accessGroup/ID/delete
	# - accessGroup/delete?accessGroup[ID1]&accessGroup[ID2]
	#
	$this->accessGroupId = $this->ses['request']['REST']['param'];
	$this->accessGroupIdList = $this->ses['request']['param']['accessgroup'];

	#
	# create accessGroup list
	#
        if (isset ($this->accessGroupId)) {
                $accessGroups[] = $this->accessGroupId;
        }

        if (isset ($this->accessGroupIdList)) {
                foreach (array_keys($this->accessGroupIdList) as $aKey) {
                        $accessGroups[] = $aKey;
                }
        }

	#
	# DELETE
	#
	if (! $this->status) {

		$this->status = AccessGroupDelete($accessGroups);

        }

	#
	# Content
	#
        $this->ses['response']['param']['accessGroups'] = $accessGroups;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
