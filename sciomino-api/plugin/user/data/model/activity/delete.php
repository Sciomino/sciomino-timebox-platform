<?

class activityDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$activitys = array();

	#
	# get params
	# - activity/ID/delete
	# - activity/delete?activity[ID1]&activity[ID2]
	#
	$this->activityId = $this->ses['request']['REST']['param'];
	$this->activityIdList = $this->ses['request']['param']['activity'];

	#
	# create activity list
	#
        if (isset ($this->activityId)) {
                $activitys[] = $this->activityId;
        }

        if (isset ($this->activityIdList)) {
                foreach (array_keys($this->activityIdList) as $aKey) {
                        $activitys[] = $aKey;
                }
        }

	#
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserActivityDelete($activitys);

        }

	#
	# Content
	#
        $this->ses['response']['param']['activitys'] = $activitys;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
