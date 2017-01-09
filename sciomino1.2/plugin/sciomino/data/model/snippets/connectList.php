<?

class connectList extends control {

    function Run() {

        global $XCOW_B;
        
		$connections = array();
		$apps = array();

		// who?
        $this->id = $this->ses['id'];

		# get connections
		$connections = OauthClientGetConnections($this->id);

		foreach($connections as $connection) {

			$apps[] = $connection['app'];

			if ($connection['app'] == 'linkedin') {
				$headers= array();
				$params = array();
				$response = OauthClientGetResponse($this->id, "linkedin", "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,public-profile-url)", "GET", $headers, $params);

				# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
				try { $xml = new SimpleXMLElement($response); } 
				catch (Exception $ignored) { } 
				$this->ses['response']['param']['linkedin'] = "not found, maybe later";
				$this->ses['response']['param']['linkedinDays'] = "60";
				$this->ses['response']['param']['linkedinReload'] = 0;

				# count the days
				$timesUp = intval((time() - $connection['timestamp']) / (60*60*24));
				$timesUp = 60 - $timesUp;
				if ($timesUp < 0) { $timesUp = 0; }
				$this->ses['response']['param']['linkedinDays'] = $timesUp;

				if (isset($xml)) {

					# if error, reconnect linkedin profile on a 401, because 60 days are probably over
					if ($xml->getName() == "error") {
						if ((string) $xml->{'status'} == "401") {
							# 1. cannot use php redirect, because this is a snippet...
							#$this->ses['response']['redirect'] = $XCOW_B['url']."/oauth/connect?app=linkedin&action=request";
							
							# 2. cannot use settimeout+window.location function, because innerHTML (in sciomino.js) does not execute javascript !?
							#$this->ses['response']['param']['linkedinReload'] = "<script type='text/javascript'>setTimeout('window.location.replace(\"".$XCOW_B['url']."/oauth/connect?app=linkedin&action=request\")', 10);</script>";
							
							# 3. hack a redirect parameter, this one is read in sciomino.js...
							#$this->ses['response']['param']['status'] = "REDIRECT:".$XCOW_B['url']."/oauth/connect?app=linkedin&action=request";
							#$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/tagDelete.php';
							
							# 4. just let the user know that the connection expired, this is save!
							$this->ses['response']['param']['linkedinReload'] = 1;
						}
					}
					else {

						$this->ses['response']['param']['linkedin'] = (string) $xml->{'first-name'}." ".(string) $xml->{'last-name'}." (".(string) $xml->headline.")";

						$linkedinId = (string) $xml->id."||".(string) $xml->{'public-profile-url'};
						if ($connection['reference'] != $linkedinId) {
							OauthClientUpdateReference($connection['id'], $linkedinId);
						}
					
					}
				}
				else {
					# problem: linkedin uses http eror response code 401, and therefor no content is available in '$response'/'$xml'
					#
					# this is a temporary solution: show reconnect link on every error (when no content is available) 
					# 
					# better solution: read the http response of errors too
					# - use: 'ignore-errors' option in httpconnect.php:getResponse
					# - BUT this should be a separate call, because a lot of code relies on empty content on an error...
					#
					if ($timesUp == 0) {
						$this->ses['response']['param']['linkedinReload'] = 1;
					}
					// always show this reload if something is wrong
					$this->ses['response']['param']['linkedinReload'] = 1;
				}
			}

			if ($connection['app'] == 'twitter') {
				$headers= array();
				$params = array();
				$response = OauthClientGetResponse($this->id, "twitter", "https://api.twitter.com/1.1/account/verify_credentials.json", "GET", $headers, $params);

				$this->ses['response']['param']['twitter'] = "not found, maybe later";
				$feed = json_decode($response, TRUE);
				if (json_last_error() == JSON_ERROR_NONE) {

					$this->ses['response']['param']['twitter'] = $feed['name']." (@".$feed['screen_name'].")";

					$twitterId = "@".$feed['screen_name'];
					if ($connection['reference'] != $twitterId) {
						OauthClientUpdateReference($connection['id'], $twitterId);
					}

				}

			}

		}

		$this->ses['response']['param']['apps'] = $apps;

     }

}

?>
