<?

class webLogin extends control {

    function Run() {

        global $XCOW_B;
        
        $id = $this->ses['id'];

		# login page for busines versions is different
		if ($XCOW_B['sciomino']['skin'] != "sciomino") {
			$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/loginBusiness.php';
		}
		
		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
