<?

class wizardLinkedin extends control {

    function Run() {

        global $XCOW_B;

		$connections = array();
		$apps = array();

		// who?
        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		// local additions
		$this->annotation = array();
		$this->organization = array();
		$this->organization['Current'] = array();
		$this->knowledge = array();
		$this->company = array();

		# get connections
		$connections = OauthClientGetConnections($this->id);

		foreach($connections as $connection) {

			$apps[] = $connection['app'];

			if ($connection['app'] == 'linkedin') {
				$myLinkedinRef = '';
				$myLinkedinId = '';
				$myLinkedinUrl = '';
				$myLinkedinRef = $myConnectionList[get_id_from_multi_array($myConnectionList, 'app', 'linkedin')]['reference'];
				list($myLinkedinId, $myLinkedinUrl) = explode('||', $myLinkedinRef);

				$headers= array();
				$params = array();
				// changed 2015-10-05
				// $response = OauthClientGetResponse($this->id, "linkedin", "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,industry,public-profile-url,location,summary,skills,three-current-positions,three-past-positions,educations,courses,publications:(id,title,url),member-url-resources)", "GET", $headers, $params);
				$response = OauthClientGetResponse($this->id, "linkedin", "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,industry,public-profile-url,location,summary,positions)", "GET", $headers, $params);

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
						
						# display the linkedin account
						$this->ses['response']['param']['linkedin'] = (string) $xml->{'first-name'}." ".(string) $xml->{'last-name'}." (".(string) $xml->headline.")";

						# store the linkedin id
						$linkedinId = (string) $xml->id."||".(string) $xml->{'public-profile-url'};
						if ($connection['reference'] != $linkedinId) {
							OauthClientUpdateReference($connection['id'], $linkedinId);
						}
						
						# only save once
						$savedLinkedin = 0;
						if (count(ScioMinoApiListKnowledge($this->userId)) > 0) {
							$savedLinkedin = 1;
						}
						if (count(ScioMinoApiListCompany($this->userId)) > 0) {
							$savedLinkedin = 1;
						}
						if (count(ScioMinoApiListWebsite($this->userId)) > 0) {
							$savedLinkedin = 1;
						}
								
						# save linkedin data
						# respect people's choice to have private linkedin accounts
						if ($savedLinkedin == 0 && (string) $xml->{'last-name'} != "private") {
							
							// personalia
							$this->annotation['description'] = (string) $xml->summary;
							$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);

							// as of 2015-10-05 (actually 2015-05-12) everything below is not available anymore
							// BUT... a new 'positions' element is added with the current positions
							if (isset($xml->positions)) {
								$count=1;
								foreach ($xml->positions->position as $position) {
									$input = array((string) $position->company->industry, (string) $position->company->name);
									if ( noEmptyInput($input) ) {
										if ($count==1) {
											$this->organization['Current']['industry'] = (string) $xml->industry;
											$this->organization['Current']['company'] = (string) $position->company->name;
											$this->organization['Current']['role'] = (string) $position->title;
											$this->organizationId = ScioMinoApiUpdateOrganization($this->organization, $this->userId);
										}
										$count++;
										$experience = array();
										// $experience['role'] = (string) $position->title;
										$experience['subject'] = (string) $position->company->industry;
										$experience['title'] = (string) $position->company->name;
										$experience['date'] = (string) $position->{'start-date'}->year;
										$experience['like'] = 2;
										$experienceId = ScioMinoApiSaveCompany($experience, $this->userId, '1');
									}
								}
							}

							// kennisvelden
							if (isset($xml->skills)) {
								foreach ($xml->skills->skill as $skill) {
									$input = array((string) $skill->skill->name);
									if ( noEmptyInput($input) ) {
										$knowledge = array();
										$knowledge['field'] = ucfirst(strtolower((string) $skill->skill->name));
										$knowledge['level'] = 1;
										$knowledgeId = ScioMinoApiSaveKnowledge($knowledge, $this->userId, '1');
									}
								}
							}

							// ervaringen met bedrijven
							if (isset($xml->{'three-current-positions'})) {
								$count=1;
								foreach ($xml->{'three-current-positions'}->position as $position) {
									$input = array((string) $position->company->industry, (string) $position->company->name);
									if ( noEmptyInput($input) ) {
										// personalia extra
										if ($count==1) {
											$this->organization['Current']['industry'] = (string) $xml->industry;
											$this->organization['Current']['company'] = (string) $position->company->name;
											$this->organization['Current']['role'] = (string) $position->title;
											$this->organizationId = ScioMinoApiUpdateOrganization($this->organization, $this->userId);
										}
										$experience = array();
										// $experience['role'] = (string) $position->title;
										$experience['subject'] = (string) $position->company->industry;
										$experience['title'] = (string) $position->company->name;
										$experience['date'] = (string) $position->{'start-date'}->year;
										$experience['like'] = 2;
										$experienceId = ScioMinoApiSaveCompany($experience, $this->userId, '1');
										$count++;
									}
								}
							}
							if (isset($xml->{'three-past-positions'})) {
								$myLinkedinInfo['past'] = array();
								foreach ($xml->{'three-past-positions'}->position as $position) {
									$input = array((string) $position->company->industry, (string) $position->company->name);
									if ( noEmptyInput($input) ) {
										$experience = array();
										// $experience['role'] = (string) $position->title;
										$experience['subject'] = (string) $position->company->industry;
										$experience['title'] = (string) $position->company->name;
										$experience['date'] = (string) $position->{'start-date'}->year;
										$experience['like'] = 2;
										$experienceId = ScioMinoApiSaveCompany($experience, $this->userId, '1');
									}
								}
							}
							
							// publicaties: websites en overig
							if (isset($xml->{'member-url-resources'})) {
								foreach ($xml->{'member-url-resources'}->{'member-url'} as $url) {
									$input = array((string) $url->url);
									if ( noEmptyInput($input) ) {
										$website = array();
										$website['title'] = (string) $url->url;
										$website['relation-self'] = (string) $url->url;
										$website['relation-self'] = urlCompletion($website['relation-self']);
										$websiteId = ScioMinoApiSaveWebsite($website, $this->userId, '1');
									}
								}
							}
							if (isset($xml->{'publications'})) {
								foreach ($xml->{'publications'}->publication as $publication) {
									$input = array((string) $publication->title);
									if ( noEmptyInput($input) ) {
										$otherPub = array();
										$otherPub['title'] = (string) $publication->title;
										$otherPub['relation-self'] = (string) $publication->url;
										$otherPub['relation-self'] = urlCompletion($otherPub['relation-self']);
										$otherPubId = ScioMinoApiSaveOtherPub($otherPub, $this->userId, '1');
									}
								}
							}

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
