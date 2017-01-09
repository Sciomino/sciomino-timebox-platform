<?

class relationDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$relations = array();

	#
	# get params
	# - relation/ID/delete
	# - relation/delete?relation[ID1]&relation[ID2]
	#
	$this->relationId = $this->ses['request']['REST']['param'];
	$this->relationIdList = $this->ses['request']['param']['relation'];

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
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserRelationDelete($relations);

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
