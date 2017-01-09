<?

class annotationSave extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->annotation = array();
	$annotationId = 0;

	#
	# get params
	#
        $this->object = $this->ses['request']['param']['object'];
        $this->object_id = $this->ses['request']['param']['object_id'];

	$this->annotation['name'] = $this->ses['request']['param']['name'];
	$this->annotation['value'] = $this->ses['request']['param']['value'];
	$this->annotation['type'] = $this->ses['request']['param']['type'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = '';}	

	#
	# NEW Annotation
	#
	if (! $this->status) {

		$annotationId = ConnectAnnotationInsert($this->annotation, $this->object, $this->object_id, $this->access);

        	if ($annotationId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['annotationId'] = $annotationId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
