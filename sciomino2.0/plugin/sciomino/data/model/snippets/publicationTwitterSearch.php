<?

class publicationTwitterSearch extends control {

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
	$this->query = $this->ses['request']['param']['query'];

	// init
	$userList = array();

	//
	// publications
	//

	// force search with your own twitter account
	$this->user = $this->userId;

	// get your twitter account
	// - if yourTwitter remains empty, the feed will not be displayed
	$userList = UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user);
	$user_sesId = $userList[$this->user]['Reference'];
	$yourTwitter = '';
	$yourConnectionList = OauthClientGetConnections($user_sesId);
	$yourTwitter = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'twitter')]['reference'];

	// content
	$this->ses['response']['param']['twitterAccount'] = $yourTwitter;
	$this->ses['response']['param']['query'] = $this->query;

    }

}

?>
