<?

class actPhotoView extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];

	// params
	$this->act = makeIntString($this->ses['request']['param']['act']);
	$this->parent = makeIntString($this->ses['request']['param']['parent']);

	// get photo
	$actString = "act[".$this->act."]&parent=".$this->parent;
	$actList = AnswersApiListActWithQuery($actString);

	$this->ses['response']['param']['photo'] = $actList[$this->act]['photo'];

     }

}

?>
