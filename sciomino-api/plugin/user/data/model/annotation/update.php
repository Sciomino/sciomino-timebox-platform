<?

class annotationUpdate extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->annotation = array();
	$annotations = array();

	$where = "";

	#
	# get params
	# - annotation/ID/update
	# - annotation/update?annotation[ID1]&annotation[ID2]
	#
	$this->annotationId = $this->ses['request']['REST']['param'];
	$this->annotationIdList = $this->ses['request']['param']['annotation'];

        $this->object = $this->ses['request']['param']['object'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->annotation['name'] = $this->ses['request']['param']['name']; } 
	if (isset($this->ses['request']['param']['value'])) { $this->annotation['value'] = $this->ses['request']['param']['value']; }
	if (isset($this->ses['request']['param']['type'])) { $this->annotation['type'] = $this->ses['request']['param']['type']; }

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
	# UPDATE
	#
	if ((! $this->status) && (count($annotations) > 0)) {
	
		$this->status = UserAnnotationUpdate($annotations, $this->annotation, $this->object);

                $userId = userAnnotationGetUserId($this->object, $annotations[0]);
                if ($userId != 0) {
                        setQueueEntry($userId, $userId);
                }

    	}
	else {
		$status = "404 Not Found";
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
