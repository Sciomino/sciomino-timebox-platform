<?

class sectionUpdate extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->section = array();
	$sections = array();

	$where = "";

	#
	# get params
	# - section/ID/update
	# - section/update?section[ID1]&section[ID2]
	#
	$this->sectionId = $this->ses['request']['REST']['param'];
	$this->sectionIdList = $this->ses['request']['param']['section'];

        $this->object = $this->ses['request']['param']['object'];

	# user changes
	if (isset($this->ses['request']['param']['name'])) { $this->section['name'] = $this->ses['request']['param']['name']; } 
	if (isset($this->ses['request']['param']['type'])) { $this->section['type'] = $this->ses['request']['param']['type']; }

	#
	# create section list
	#
        if (isset ($this->sectionId)) {
                $sections[] = $this->sectionId;
        }

        if (isset ($this->sectionIdList)) {
                foreach (array_keys($this->sectionIdList) as $aKey) {
                        $sections[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($sections) > 0)) {
	
		$this->status = UserSectionUpdate($sections, $this->section, $this->object);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['sections'] = $sections;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
