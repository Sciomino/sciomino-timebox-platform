<?

class listList extends control {

    function Run() {

        global $XCOW_B;
        
	$listList = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	$this->user = $this->ses['request']['param']['user'];
	$this->mode = $this->ses['request']['param']['mode'];

	# get list list
	$query = "type=private&userId=".$this->userId;
	$listList = UserApiGroupListWithQuery($query);

	# if user is in group, then CHECK
	foreach ($listList as $listKey => $listVal) {
		$listList[$listKey]['Checked'] = '';
		if (is_array($listVal['User'])) {	
			foreach ($listVal['User'] as $userKey => $userVal) {
				if ($this->user == $userKey) {	
					$listList[$listKey]['Checked'] = 'checked';		
				}
			}
		}
	}

	if ($this->mode == "view") {
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/listList2.php';
	}

	$this->ses['response']['param']['user'] = $this->user;
	$this->ses['response']['param']['listList'] = $listList;

     }

}

?>
