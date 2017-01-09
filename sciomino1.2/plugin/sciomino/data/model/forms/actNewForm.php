<?

class actNew extends control {

    function Run() {

        global $XCOW_B;

	$actId = 0;
	$this->act = array();

	// who?
        $this->id = $this->ses['id'];
	// userId only used for list of knowledge fields
	$this->userId = UserApiGetUserFromReference($this->id);

	// params
	$this->go = $this->ses['request']['param']['go'];
	$this->act['description'] = $this->ses['request']['param']['com_description'];
	$this->act['expiration'] = $this->ses['request']['param']['com_expiration'];
	$this->k = $this->ses['request']['param']['k'];
	$this->h = $this->ses['request']['param']['h'];
	$this->net = $this->ses['request']['param']['net'];
    if (! isset($this->net)) {$this->net = 0;}

	//
	// check fields?
	//
	if ($this->go != $this->ses['time']) {
		$this->status = "Intruder alert (CSRF)";
	}
	$input = array($this->act['description'], $this->act['expiration']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save, BEWARE save with id, NOT with userId
		$actId = AnswersApiSaveAct ($this->act, $this->id, '1'); 

		if ($actId != 0) {
			$this->status = language('sciomio_text_act_new_status_ok');
		}
		else{
			$this->status = language('sciomio_text_act_new_status_wrong');
		}

		// add knowledge en hobby
		if (count($this->k) > 0) {
			$profile = array();
			$profile['group'] = 'knowledgefield';
			foreach ($this->k as $k) {
				$knowledge = array();
				$knowledge['field'] = $k;
				AnswersApiSaveActProfileAnnotationList($profile, $knowledge, $actId, '1');
			}
		}
		if (count($this->h) > 0) {
			$profile = array();
			$profile['group'] = 'hobbyfield';
			foreach ($this->h as $h) {
				$hobby = array();
				$hobby['field'] = $h;
				AnswersApiSaveActProfileAnnotationList($profile, $hobby, $actId, '1');
			}

		}
		// add network
		if ($this->net != '0') {
			$anno = array();
			$anno['network'] = $this->net;
			AnswersApiSaveActAnnotationList($anno, $actId, '1'); 
		}

		$this->ses['response']['param']['actId'] = $actId;
		$this->ses['response']['param']['actDescription'] = $this->act['description'];
		$this->ses['response']['param']['actExpiration'] = $this->act['expiration'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/actNewForm2.php';
        }
        
		// show the form
        else {

			$query = "type=public&order=name";
			$networkList = UserApiGroupListWithQuery($query);
			$this->ses['response']['param']['networkList'] = $networkList;

        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
