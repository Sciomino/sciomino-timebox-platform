<?

class getActIds extends control {

    function Run() {

        global $XCOW_B;

		# only get act ids of latest reactions, with a limit of 100
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 100;}

		# default last hour
		$this->to = $this->ses['request']['param']['to'];
		if (! isset($this->to)) {$this->to = time();}
		$this->from = $this->ses['request']['param']['from'];
		if (! isset($this->from)) {$this->from = $this->to - 3600;}

		# get ActId's from offset to limit, based on new reacts
		$actList = array();
		$actIdList = array();
		$actList = AnswersApiListActWithQuery("parent=-1&format=short&order=time&direction=desc&limit=".$this->limit);
		
		foreach ($actList as $act) {
			if ($act['Timestamp'] >= $this->from && $act['Timestamp'] <= $this->to) {
				# need act id's not react id's
				$actIdList[] = $act['Parent'];
			}
		}

		# remove duplicates (there can be more new reactions to the same act)
		$actIdList = array_unique($actIdList);

		# output
		$this->ses['response']['param']['status'] = implode(",",$actIdList);

    }

}

?>
