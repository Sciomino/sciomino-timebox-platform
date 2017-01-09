<?

class relationSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->relation = array();
	$relationId = 0;

	#
	# get params
	#
	$this->relation['userId'] = $this->ses['request']['param']['relation'];

	# reference
        $this->userId = $this->ses['request']['param']['userId'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = '';}	

	#
	# NEW RELATION
	#
	if (! $this->status) {

		$relationId = UserRelationInsert($this->relation, $this->userId, $this->access);

        	if ($relationId == 0) {
			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['relationId'] = $relationId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
