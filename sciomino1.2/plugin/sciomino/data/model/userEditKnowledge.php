<?

class userEditKnowledge extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// get my knowledge list
	$myKnowledgeList = array();
	$myKnowledgeList = ScioMinoApiListKnowledge($this->userId);

	# get businessunit skills
	$organizationList = array();
	$organizationList = ScioMinoApiListOrganization($this->userId);
	$my_unit = $organizationList[get_id_from_multi_array($organizationList, 'Name', 'Current')]['division'];
	$searchList = array();
	$searchList = UserApiListSearchWithQuery("userId=".$this->userId."&p[businessunit]=".urlencode($my_unit)."&detail=knowledgeOnly");

	$count = 0;
	$knowledgeList = array();
	foreach($searchList['knowledge'] as $key => $val) {
		if ( get_id_from_multi_array($myKnowledgeList, 'field', $key) == 0 ) {
			$knowledgeList[$key] = $val;
			$count++;
			if ($count > 4) {
				break;
			}
		}
	}
	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

    }

}

?>
