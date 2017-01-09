<?

class tweetNew extends control {

    function Run() {

        global $XCOW_B;

		// who?
		$this->id = $this->ses['id'];

		// params
		$this->user = $this->ses['request']['param']['user'];
		$this->tweet = $this->ses['request']['param']['com_tweet'];

		$twitterEnabled = 0;	
		# get connections
		$connections = OauthClientGetConnections($this->id);
		if (get_id_from_multi_array($connections, 'app', 'twitter') != 0) {
			$twitterEnabled = 1;
		}

		//
		// check fields?
		//
		$input = array($this->user, $this->tweet);
		if (! noEmptyInput($input) ) {
			$this->status = "Input Error";
		}

		//
		// if the fields are checked, go for it
		// otherwise proceed to the view and show a form where a new mail can be entered
		//
		if (! $this->status && $twitterEnabled) {

			// make sure it's a valid string
			$this->tweet = substr($this->tweet, 0, 140);

			# tweet
			$headers = array();
			$params = array();
			$params['status'] = $this->tweet;
			$response = OauthClientGetResponse($this->id, "twitter", "https://api.twitter.com/1.1/statuses/update.json", "POST", $headers, $params);

			$feed = json_decode($response, TRUE);
			if (json_last_error() == JSON_ERROR_NONE) {
				$this->status = language('sciomio_text_twitter_new_status_ok');
			}
			else {
				$this->status = language('sciomio_text_twitter_new_status_wrong');
			}

			$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/tweetNewForm2.php';

		}
			
		// show the form
		else {
			$this->ses['response']['param']['user'] = $this->user;
			$this->ses['response']['param']['twitterOk'] = $twitterEnabled;
		}

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
