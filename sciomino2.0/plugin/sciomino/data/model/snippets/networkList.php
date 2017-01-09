<?

class networkList extends control {

    function Run() {

        global $XCOW_B;
        
	$networkList = array();

	// who?
    $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	$this->mode = $this->ses['request']['param']['mode'];

	# get network list
	$query = "type=public&order=name";
	$networkList = UserApiGroupListWithQuery($query);

	# if user is in group, then CHECK
	foreach ($networkList as $networkKey => $networkVal) {
		$networkList[$networkKey]['Checked'] = '';
		if (is_array($networkVal['User'])) {	
			foreach ($networkVal['User'] as $userKey => $userVal) {
				if ($this->userId == $userKey) {	
					$networkList[$networkKey]['Checked'] = 'checked';
					break;		
				}
			}
		}
	}

	if ($this->mode == "view") {
      	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/networkList2.php';
	}

	$this->ses['response']['param']['user'] = $this->userId;
	$this->ses['response']['param']['networkList'] = $networkList;

     }

}

?>
