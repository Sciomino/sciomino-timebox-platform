<?

class relationUpdate extends control {

   function Run() {

        global $XCOW_B;

	#
	# init
	#
	$this->relation = array();
	$relations = array();

	$where = "";

	#
	# get params
	# - relation/ID/update
	# - relation/update?relation[ID1]&relation[ID2]
	#
	$this->relationId = $this->ses['request']['REST']['param'];
	$this->relationIdList = $this->ses['request']['param']['relation'];

	# references
	# $this->userId = $this->ses['request']['param']['userId'];

	# user changes
	if (isset($this->ses['request']['param']['access'])) { $this->relation['access'] = $this->ses['request']['param']['access']; }

	#
	# create relation list
	#
        if (isset ($this->relationId)) {
                $relations[] = $this->relationId;
        }

        if (isset ($this->relationIdList)) {
                foreach (array_keys($this->relationIdList) as $aKey) {
                        $relations[] = $aKey;
                }
        }

	#
	# UPDATE
	#
	if ((! $this->status) && (count($relations) > 0)) {
	
		$this->status = UserRelationUpdate($relations, $this->relation);

    	}
	else {
		$status = "404 Not Found";
	}

	#
	# Content
	#
	$this->ses['response']['param']['relations'] = $relations;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
