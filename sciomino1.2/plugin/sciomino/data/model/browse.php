<?

class browse extends control {

    function Run() {

        global $XCOW_B;
        
        $statsList = array();

        $id = $this->ses['id'];

        // get stats
        $statsList = UserApiListStats("SC_UserApiListStats");
        $statsList = current($statsList);

        // content
        $this->ses['response']['param']['stats'] = $statsList;
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
