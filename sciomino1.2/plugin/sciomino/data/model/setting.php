<?

class setting extends control {

    function Run() {

        global $XCOW_B;
        
        $id = $this->ses['id'];

	// param
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
	$this->ses['response']['param']['view'] = $XCOW_B['sciomino']['shortcut-view'];

     }

}

?>
