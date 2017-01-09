<?

class userViewCard extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->user = $this->ses['request']['param']['user'];
	if (! isset($this->user)) { $this->user = $this->userId; }

	// init
	$itsMe = 0;
	$userList = array();

	// is it me?
	// echo "VIEWER:".$this->userId;
	// echo "USER:".$this->user;
	if ($this->userId == $this->user) {
		$itsMe = 1;
	}
	
	//
	// go
	//
	$userList = UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user);

	// twitter
	$user_sesId = $userList[$this->user]['Reference'];
	$yourConnectionList = OauthClientGetConnections($user_sesId);
	$yourTwitter = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'twitter')]['reference'];

	// content
	$this->ses['response']['param']['me'] = $itsMe;
	$this->ses['response']['param']['view'] = $this->user;
	$this->ses['response']['param']['meUser'] = $this->userId;
	$this->ses['response']['param']['user'] = $userList[$this->user];

	$this->ses['response']['param']['twitterAccount'] = $yourTwitter;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
