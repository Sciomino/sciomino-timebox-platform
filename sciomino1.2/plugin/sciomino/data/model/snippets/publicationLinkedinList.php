<?

class publicationLinkedinList extends control {

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
	$skillList = array();

	// get my knowledge list
	$knowledgeList = array();
	$knowledgeList = ScioMinoApiListKnowledge($this->userId);

	//
	// publications
	//

	// - connections
	$myConnectionList = OauthClientGetConnections($this->id);
	$myLinkedinRef = '';
	$myLinkedinId = '';
	$myLinkedinUrl = '';
	$myLinkedinRef = $myConnectionList[get_id_from_multi_array($myConnectionList, 'app', 'linkedin')]['reference'];
	list($myLinkedinId, $myLinkedinUrl) = explode('||', $myLinkedinRef);

	$userList = UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user);
	$user_sesId = $userList[$this->user]['Reference'];
	$yourConnectionList = OauthClientGetConnections($user_sesId);
	$yourLinkedinRef = '';
	$yourLinkedinId = '';
	$yourLinkedinUrl = '';
	$yourLinkedinRef = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'linkedin')]['reference'];
	list($yourLinkedinId, $yourLinkedinUrl) = explode('||', $yourLinkedinRef);

	// - linkedin friendship (needs authentication!)
	$yourLinkedinInfo = array();
	$yourLinkedinInfo['account'] = $yourLinkedinId;
	$yourLinkedinInfo['url'] = $yourLinkedinUrl;
	$yourLinkedinInfo['mode'] = "public";

	// only fetch mode 'private' for the users own profiel as of 2013-11-15 (added myId == yourId below)
	if ($myLinkedinRef != '' && $yourLinkedinRef != '' && $myLinkedinId == $yourLinkedinId) {
		$headers= array();
		$params = array();
		$response = OauthClientGetResponse($this->id, "linkedin", "https://api.linkedin.com/v1/people/id=".$yourLinkedinId.":(first-name,last-name,headline,industry,distance,summary,specialties,interests,skills,three-current-positions,three-past-positions,educations,public-profile-url)", "GET", $headers, $params);

		# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
		try { $xml = new SimpleXMLElement($response); } 
		catch (Exception $ignored) { } 

		if (isset($xml)) {
			# if error, reconnect linkedin profile on a 401, because 60 days are probably over
			if ($xml->getName() == "error") {
				if ((string) $xml->{'status'} == "401") {
					
					$yourLinkedinInfo['url'] = $XCOW_B['url']."/oauth/connect?app=linkedin&action=request";
					$yourLinkedinInfo['mode'] = "expired";
					
				}
			}
			else {
			
				# respect people's choice to have private linkedin accounts
				if ((string) $xml->{'last-name'} != "private") {
					$yourLinkedinInfo['mode'] = "private";
					$yourLinkedinInfo['name'] = (string) $xml->{'first-name'}." ".(string) $xml->{'last-name'};
					$yourLinkedinInfo['headline'] = (string) $xml->headline;
					$yourLinkedinInfo['industry'] = (string) $xml->industry;
					if ((string) $xml->distance == "-1") { $yourLinkedinInfo['distance'] = "Out of your network"; }
					if ((string) $xml->distance == "0") { $yourLinkedinInfo['distance'] = "You"; }
					if ((string) $xml->distance == "1") { $yourLinkedinInfo['distance'] = "1<sup>st</sup>"; }
					if ((string) $xml->distance == "2") { $yourLinkedinInfo['distance'] = "2<sup>nd</sup>"; }
					if ((string) $xml->distance == "3") { $yourLinkedinInfo['distance'] = "3<sup>rd</sup>rd"; }
					$yourLinkedinInfo['summary'] = (string) $xml->summary;
					$yourLinkedinInfo['specialties'] = (string) $xml->specialties;
					$yourLinkedinInfo['interests'] = (string) $xml->interests;
					$yourLinkedinInfo['link'] = (string) $xml->{'public-profile-url'};
					if (isset($xml->{'three-current-positions'})) {
						foreach ($xml->{'three-current-positions'}->position as $position) {
							$yourLinkedinInfo['current'] .= "* " . (string) $position->title . " at ". (string) $position->company->name . "<br/>";
						}
					}
					if (isset($xml->{'three-past-positions'})) {
						foreach ($xml->{'three-past-positions'}->position as $position) {
							$yourLinkedinInfo['past'] .= "* " . (string) $position->title . " at ". (string) $position->company->name . "<br/>";
						}
					}
					if (isset($xml->educations)) {
						foreach ($xml->educations->education as $education) {
							$yourLinkedinInfo['education'] .= "* " . (string) $education->{'field-of-study'} . " at ". (string) $education->{'school-name'} . "<br/>";
						}
					}
					$firstSkill = 0;
					if (isset($xml->skills)) {
						foreach ($xml->skills->skill as $skill) {
							if ($firstSkill == 0) {
								$yourLinkedinInfo['skills'] = (string) $skill->skill->name;
								$firstSkill++;
							}
							else {
								$yourLinkedinInfo['skills'] .= ", " . (string) $skill->skill->name;
							}
							
							// this is a new skill list, not yet in sciomino
							// only usable on a persons own profile
							$inSkill = (string) $skill->skill->name;
							if ( get_id_from_multi_array($knowledgeList, 'field', ucfirst(strtolower($inSkill))) == 0 ) {
								$skillList[] = $inSkill;
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
			$yourLinkedinInfo['url'] = $XCOW_B['url']."/oauth/connect?app=linkedin&action=request";
			$yourLinkedinInfo['mode'] = "expired";
		}
	}

	// content
	$this->ses['response']['param']['yourLinkedinInfo'] = $yourLinkedinInfo;
	$this->ses['response']['param']['linkedinSkillList'] = $skillList;

     }

}

?>
