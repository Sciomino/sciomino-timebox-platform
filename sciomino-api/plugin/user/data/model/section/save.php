<?

class sectionSave extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->section = array();
	$sectionId = 0;

	#
	# get params
	#
        $this->object = $this->ses['request']['param']['object'];
        $this->object_id = $this->ses['request']['param']['object_id'];

	$this->section['name'] = $this->ses['request']['param']['name'];
	$this->section['type'] = $this->ses['request']['param']['type'];
	$this->access = $this->ses['request']['param']['access'];
	if (! isset($this->access)) {$this->access = '';}	

	#
	# NEW Section
	#
	if (! $this->status) {

		$sectionId = UserSectionInsert($this->section, $this->object, $this->object_id, $this->access);

        	if ($sectionId == 0) {
 			$this->status = "500 Internal Error";
        	}

        }

	#
	# Content
	#
        $this->ses['response']['param']['sectionId'] = $sectionId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
