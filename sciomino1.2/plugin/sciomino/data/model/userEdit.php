<?

class userEdit extends control {

    function Run() {

        global $XCOW_B;
        
        $id = $this->ses['id'];

	// param
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
