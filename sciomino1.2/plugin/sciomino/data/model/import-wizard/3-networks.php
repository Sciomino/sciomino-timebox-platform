<?

class wizardNetworks extends control {

    function Run() {

        global $XCOW_B;

		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

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

		$this->ses['response']['param']['user'] = $this->userId;
		$this->ses['response']['param']['networkList'] = $networkList;
        	
    }

}

?>
