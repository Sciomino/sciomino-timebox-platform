<?

class reviewDelete extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$reviews = array();

	#
	# get params
	# - review/ID/delete
	# - review/delete?review[ID1]&review[ID2]
	#
	$this->reviewId = $this->ses['request']['REST']['param'];
	$this->reviewIdList = $this->ses['request']['param']['review'];

	#
	# create connect list
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
	# DELETE
	#
	if (! $this->status) {

		$this->status = ReviewDelete($reviews);

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
