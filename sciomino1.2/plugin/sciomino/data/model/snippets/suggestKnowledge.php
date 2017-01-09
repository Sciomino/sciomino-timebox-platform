<?

class suggestKnowledge extends control {

    function Run() {

        global $XCOW_B;
    
	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
 
	// what?
	$this->query = $this->ses['request']['param']['query'];

	// frontend uses term...
	$this->term = $this->ses['request']['param']['term'];

	if (empty($this->query)) {
		$this->query = $this->term;
	}

	# get remote suggest list
	$connectList = array();
	$connectLanguage = "";
	if ($this->ses['response']['language'] != "nl") {
		$connectLanguage = "-".$this->ses['response']['language'];
	}
	$connectString = "type=".$XCOW_B['sciomino']['connect-wiki'].$connectLanguage."&limit=10";
	$connectString .= "&query=".urlencode($this->query);
	# make the wiki suggest optional
	if ($XCOW_B['sciomino']['suggest-wiki-on'] == 1) {
		$connectList = ConnectApiListConnectWithQuery($connectString);
	}

	# get local suggest list
	$lSearchList = array();
	$lQuery = array();
	$lQuery['context'] = "knowledge";
	$lQuery['userId'] = $this->userId;
	$lQuery['limit'] = 10;
	$lQuery['start'] = $this->query;
	$lSearchList = UserApiListListAll($lQuery);

	# merge
	$suggestList = array();
	foreach ($lSearchList['knowledge'] as $knowledgeKey => $knowledgeVal) {
		$suggestList[]['name'] = $knowledgeKey;
	}
	if (count($connectList) > 0) {
		$suggestList[]['name'] = "-----";
		$suggestList = array_merge($suggestList, $connectList);
	}

	# content
	$this->ses['response']['param']['suggestList'] = $suggestList;

     }

}

?>
