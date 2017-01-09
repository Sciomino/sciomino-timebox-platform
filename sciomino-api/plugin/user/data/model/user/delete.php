<?

class userDelete extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$users = array();

	#
	# get params
	# - user/ID/delete
	# - user/delete?user[ID1]&user[ID2]
	#
	$this->userId = $this->ses['request']['REST']['param'];
	$this->userIdList = $this->ses['request']['param']['user'];

	#
	# create user list
	#
        if (isset ($this->userId)) {
                $users[] = $this->userId;
        }

        if (isset ($this->userIdList)) {
                foreach (array_keys($this->userIdList) as $aKey) {
                        $users[] = $aKey;
                }
        }

	#
	# DELETE
	#
	if (! $this->status) {

		$this->status = UserDelete($users);

        }

	#
	# Content
	#
        $this->ses['response']['param']['users'] = $users;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
