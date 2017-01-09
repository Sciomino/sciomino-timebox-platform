<?

class webLogin extends control {

    function Run() {

        global $XCOW_B;
        
        $id = $this->ses['id'];

		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
