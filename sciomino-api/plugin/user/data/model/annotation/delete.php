<?

class annotationDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$annotations = array();

	#
	# get params
	# - annotation/ID/delete
	# - annotation/delete?annotation[ID1]&annotation[ID2]
	#
	$this->annotationId = $this->ses['request']['REST']['param'];
	$this->annotationIdList = $this->ses['request']['param']['annotation'];

        $this->object = $this->ses['request']['param']['object'];

	#
	# create annotation list
	#
        if (isset ($this->annotationId)) {
                $annotations[] = $this->annotationId;
        }

        if (isset ($this->annotationIdList)) {
                foreach (array_keys($this->annotationIdList) as $aKey) {
                        $annotations[] = $aKey;
                }
        }

	#
	# DELETE
	#
	if (! $this->status) {

		# check user before delete :-)
                $userId = userAnnotationGetUserId($this->object, $annotations[0]);
                if ($userId != 0) {
                        setQueueEntry($userId, $userId);
                }

		$this->status = UserAnnotationDelete($annotations, $this->object);

        }

	#
	# Content
	#
        $this->ses['response']['param']['annotations'] = $annotations;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
