<?

class importWizard extends control {

    function Run() {

        global $XCOW_B;
        
        $this->id = $this->ses['id'];

		$this->step = $this->ses['request']['param']['step'];
		if (! isset($this->step)) {$this->step = 1;}
		$this->ses['response']['param']['step'] = $this->step;

		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
