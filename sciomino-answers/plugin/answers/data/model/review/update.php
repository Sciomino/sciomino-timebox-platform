<?

class reviewUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->review = array();
	$reviews = array();

	$where = "";

	#
	# get params
	# - review/ID/update
	# - review/update?review[ID1]&review[ID2]
	#
	$this->reviewId = $this->ses['request']['REST']['param'];
	$this->reviewIdList = $this->ses['request']['param']['review'];

	# review changes
	if (isset($this->ses['request']['param']['score'])) { $this->review['score'] = $this->ses['request']['param']['score']; } 

	#
	# create act list
	#
        if (isset ($this->reviewId)) {
                $reviews[] = $this->reviewId;
        }

        if (isset ($this->reviewIdList)) {
                foreach (array_keys($this->reviewIdList) as $aKey) {
                        $reviews[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($reviews) > 0)) {
	
		$this->status = ReviewUpdate($reviews, $this->review);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['reviews'] = $reviews;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
