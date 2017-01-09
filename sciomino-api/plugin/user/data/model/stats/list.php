<?

class statsList extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$statsList = array();

	#
	# get params
	#
	$this->mode = $this->ses['request']['param']['mode'];
    if (! isset($this->mode)) {$this->mode = 'insights';}

	$this->from = $this->ses['request']['param']['from'];
    if (! isset($this->from)) {$this->from = 0;}
	$this->to = $this->ses['request']['param']['to'];
    if (! isset($this->to)) {$this->to = 0;}

	#
    # Get List of the latest stats!
    # - the latest between from and to
    # - else the absolute latest
    #
    if ($this->from > 0 || $this->to > 0) {
		$statsList = StatsListBetweenDates($this->from, $this->to);
		$statsList = current($statsList);		
	}
	else {
		$statsList = StatsList();
		$statsList = current($statsList);
	}

	#
	# Summary
	#

	#
	# Content
	#
	$this->ses['response']['param']['mode'] = $this->mode;
	$this->ses['response']['param']['statsList'] = $statsList;

   }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
