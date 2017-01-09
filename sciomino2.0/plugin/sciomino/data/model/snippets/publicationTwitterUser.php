<?

class publicationTwitterUser extends control {

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
	$userList = array();

	//
	// publications
	//

	// - connections
	$myTwitter = '';
	$myConnectionList = OauthClientGetConnections($this->id);
	$myTwitter = $myConnectionList[get_id_from_multi_array($myConnectionList, 'app', 'twitter')]['reference'];

	$userList = UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user);
	$user_sesId = $userList[$this->user]['Reference'];
	$yourTwitter = '';
	$yourConnectionList = OauthClientGetConnections($user_sesId);
	$yourTwitter = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'twitter')]['reference'];

	// - twitter friendship
	$myTwitterInfo = array();
	$myTwitterInfo['account'] = $myTwitter;
	$myTwitterInfo['displayFollow'] = 0;
	
	if ($myTwitter != '' && $yourTwitter != '' && $myTwitter != $yourTwitter) {
		
		# as of twitter api version 1.1 requests should be authenticated, this is not cacheable at the moment...
		$headers = array();
		$params = array();
		$response = OauthClientGetResponse($this->id, "twitter", "https://api.twitter.com/1.1/friendships/show.json?source_screen_name=".$myTwitter."&target_screen_name=".$yourTwitter, "GET", $headers, $params);

		$feed = json_decode($response, TRUE);
		if (json_last_error() == JSON_ERROR_NONE) {

			$myTwitterInfo['displayFollow'] = 1;
			$myTwitterInfo['following'] = $feed['relationship']['source']['following'];
			$myTwitterInfo['followedby'] = $feed['relationship']['source']['followed_by'];
			
			if ($myTwitterInfo['following'] == "1") {$myTwitterInfo['following'] = "true";}
			if ($myTwitterInfo['following'] == "0") {$myTwitterInfo['following'] = "false";}
			if ($myTwitterInfo['followedby'] == "1") {$myTwitterInfo['followedby'] = "true";}
			if ($myTwitterInfo['followedby'] ==  "0") {$myTwitterInfo['followedby'] = "false";}

		}

	}

	// content
	$this->ses['response']['param']['twitterAccount'] = $yourTwitter;
	$this->ses['response']['param']['myTwitterInfo'] = $myTwitterInfo;

    }

}

?>
