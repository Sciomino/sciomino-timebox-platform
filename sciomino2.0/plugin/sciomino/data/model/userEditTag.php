<?

class userEditTag extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// get my tag list
	$myTagList = array();
	$myTagList = ScioMinoApiListTag($this->userId);

	# get businessunit tags
	$organizationList = array();
	$organizationList = ScioMinoApiListOrganization($this->userId);
	$my_unit = $organizationList[get_id_from_multi_array($organizationList, 'Name', 'Current')]['division'];
	$searchList = array();
	$searchList = UserApiListSearchWithQuery("userId=".$this->userId."&p[businessunit]=".urlencode($my_unit)."&detail=tagOnly");

	$count = 0;
	$tagList = array();
	foreach($searchList['tag'] as $key => $val) {
		if ( get_id_from_multi_array($myTagList, 'name', $key) == 0 ) {
			$tagList[$key] = $val;
			$count++;
			if ($count > 4) {
				break;
			}
		}
	}
	$this->ses['response']['param']['tagList'] = $tagList;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

    }

}

?>
