<?

class insightsSocial extends control {

    function Run() {

        global $XCOW_B;

	$statsList = array();
	$socialList = array();
     
        $id = $this->ses['id'];

	// read input
        $this->list = $this->ses['request']['param']['list'];
        if (! isset($this->list)) {$this->list = 'blog';}

	// get stats
	$statsList = UserApiListStats();
	$statsList = UserApiListStats("SC_UserApiListStats");

	// get social
	// loaded in snippet...

	// content
	$this->ses['response']['param']['stats'] = current($statsList);
	$this->ses['response']['param']['socialList'] = $socialList;
	$this->ses['response']['param']['list'] = $this->list;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

	
     }

}

?>
