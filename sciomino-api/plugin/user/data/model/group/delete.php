<?

class groupDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$groups = array();

	#
	# get params
	# - group/ID/delete
	# - group/delete?group[ID1]&group[ID2]
	#
	$this->groupId = $this->ses['request']['REST']['param'];
	$this->groupIdList = $this->ses['request']['param']['group'];

	#
	# create group list
	#
        if (isset ($this->groupId)) {
                $groups[] = $this->groupId;
        }

        if (isset ($this->groupIdList)) {
                foreach (array_keys($this->groupIdList) as $aKey) {
                        $groups[] = $aKey;
                }
        }

	#
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserGroupDelete($groups);

        }

	#
	# Content
	#
        $this->ses['response']['param']['groups'] = $groups;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
