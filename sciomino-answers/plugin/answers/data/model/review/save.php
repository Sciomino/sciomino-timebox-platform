<?

class reviewSave extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->review = array();
	$reviewId = 0;

	#
	# get params
	#
	# defaults:
	# - score (= 1)
	$this->review['score'] = $this->ses['request']['param']['score'];
        if (! isset($this->review['score'])) {$this->review['score'] = 1;}

	$this->act = $this->ses['request']['param']['act'];
	$this->reference = $this->ses['request']['param']['reference'];

        #
        # check reference
        #
        if (! isset($this->reference) ) {
		$this->reference = "";
        }

	#
	# NEW
	#
	if (! $this->status) {

		$reviewId = ReviewInsert($this->review, $this->act, $this->reference);

        	if ($reviewId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['reviewId'] = $reviewId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
