<?

class mypage extends control {

    function Run() {

        global $XCOW_B;
        
        $id = $this->ses['id'];

	if (isset($id) ) {
		$this->ses['response']['param']['login'] = 1;
	}
	else {
		$this->ses['response']['param']['login'] = 0;
	}

    }

}

?>
