<?

class publicationTwitterFeed extends control {

    function Run() {

    global $XCOW_B;
        
	//
	// who?
	//
    $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->type = $this->ses['request']['param']['type'];
	$this->query = $this->ses['request']['param']['query'];

	// only get a feed for the logged-in user
	$this->user = $this->userId;
	$this->tag = "";
	
	if ($this->type == "user") {
		if (isset($this->query)) {
			$this->user = $this->query; 
		}
	}
	elseif ($this->type == "tag") {
		if (isset($this->query)) {
			$this->tag = $this->query; 
		}
	}

	// init
	$userList = array();

	//
	// publications
	//

	// get your twitter account
	// - if yourTwitter remains empty, the feed will not be displayed
	$userList = UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user);
	$user_sesId = $userList[$this->user]['Reference'];
	$yourTwitter = '';
	$yourConnectionList = OauthClientGetConnections($user_sesId);
	$yourTwitter = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'twitter')]['reference'];

	// - twitter tweets
	if ($yourTwitter != '') {

		# as of twitter api version 1.1 requests should be authenticated, this is not cacheable at the moment...
		$headers = array();
		$params = array();
		# NOTE: get twitter feed on behalf of the user who is viewed!!!
		#       - wanted to use public profile, just like linkedin, but there is no public timeline for twitter.
		# NOTE 2: get 20 tweets, to account for deleted tweets and retweets!!!
		if ($this->type == "tag") {			
			//$response = OauthClientGetResponse($user_sesId, "twitter", "https://api.twitter.com/1.1/search/tweets.json?count=20&q=".urlencode($this->tag)."&lang=".$this->ses['response']['language'], "GET", $headers, $params);
			if ($this->tag != '') {
				$response = OauthClientGetResponse($user_sesId, "twitter", "https://api.twitter.com/1.1/search/tweets.json?include_entities=false&count=30&q=".urlencode($this->tag), "GET", $headers, $params);
			}
			else {
				$response = "";
			}
		}
		else {
			$response = OauthClientGetResponse($user_sesId, "twitter", "https://api.twitter.com/1.1/statuses/user_timeline.json?exclude_replies=true&count=30&screen_name=".$yourTwitter, "GET", $headers, $params);
		}

		$feed = json_decode($response, TRUE);
		if ((json_last_error() == JSON_ERROR_NONE) && ($response != "")) {
						
			// for search list
			if (is_array($feed['statuses'])) {
				$noRetweetsPlease = array();
				foreach ($feed['statuses'] as $tweet) {
					if (! is_array($tweet['retweeted_status'])) {
						$noRetweetsPlease[] = $tweet;
					}
				}
				if (count($noRetweetsPlease) > 0) {
					$response = json_encode($noRetweetsPlease);
				}
			}

		}
		else {
			$response = "[]";
		}
			
	}
	else {
		$response = "[]";
	}

	// content
	$this->ses['response']['param']['twitterAccount'] = $yourTwitter;
	$this->ses['response']['param']['response'] = $response;

    }

}

?>
