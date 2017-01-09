<?

class sectionDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$sections = array();

	#
	# get params
	# - section/ID/delete
	# - section/delete?section[ID1]&section[ID2]
	#
	$this->sectionId = $this->ses['request']['REST']['param'];
	$this->sectionIdList = $this->ses['request']['param']['section'];

        $this->object = $this->ses['request']['param']['object'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserSectionDelete($sections, $this->object);

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
