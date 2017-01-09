<?

class publicationLinkedinSkillsList extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// get my knowledge list
	$knowledgeList = array();
	$knowledgeList = ScioMinoApiListKnowledge($this->userId);

	# get linkedin skills
	$skillList = array();
	$headers = array();
	$params = array();

	// disabled as of 2015-10-05
	/*
	if (OauthClientGetCredentialId($this->id, 'linkedin') > 0) {
		$response = OauthClientGetResponse($this->id, "linkedin", "https://api.linkedin.com/v1/people/~/skills", "GET", $headers, $params);

		# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
		try { $xml = new SimpleXMLElement($response); } 
		catch (Exception $ignored) { } 

		if (isset($xml)) {
			foreach ($xml->skill as $skill) {
				$inSkill = (string) $skill->skill->name;
				if ( get_id_from_multi_array($knowledgeList, 'field', ucfirst(strtolower($inSkill))) == 0 ) {
					$skillList[] = $inSkill;
				}
			}
		}

	}
	*/
	
	$this->ses['response']['param']['linkedinSkillList'] = $skillList;

    }

}

?>
