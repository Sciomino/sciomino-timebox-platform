<?

class searchPerson extends control {

    function Run() {

        global $XCOW_B;

	$userString = "";
	$userList = array();
        
	// who?
        $this->id = $this->ses['id'];
	//$this->userId = UserApiGetUserFromReference($this->id);
 
	$this->query = $this->ses['request']['param']['query'];

	// frontend uses term...
	$this->term = $this->ses['request']['param']['term'];

	if (empty($this->query)) {
		$this->query = $this->term;
	}

	$userString .= "query=".urlencode($this->query);
	//$userString .= "&format=short";
	$userString .= "&limit=10";

	# get person list
	$userList = UserApiListUserWithQuery($userString);

	# content
	$this->ses['response']['param']['userList'] = $userList;

     }

}

?>
