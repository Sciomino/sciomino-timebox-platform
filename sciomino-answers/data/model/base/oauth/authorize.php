<?php

class OauthAuthorize extends control {

    function Run() {

	global $XCOW_B;

	$clientInfo = array();
	$authorized = 0;

	# call from client
	$oauth_token = $this->ses['request']['param']['oauth_token'];

	# return from auth form
	$token = $this->ses['request']['param']['token'];
	$access = $this->ses['request']['param']['access'];
	$user = $this->ses['request']['param']['user'];
	$pass = $this->ses['request']['param']['pass'];

	if ($oauth_token) {
		$clientInfo = OauthGetClientFromToken($oauth_token);

		$this->ses['response']['param']['clientName'] = $clientInfo['name'];
		$this->ses['response']['param']['clientDescription'] = $clientInfo['description'];
		$this->ses['response']['param']['token'] = $oauth_token;
		$this->ses['response']['param']['error'] = "";
	}
	elseif ($token) {
		if (checkLogin($user, $pass)) {
			if ($access == "yes") {
				$authorized = 1;			
			}
			$sessionId = getUserIdFromUserName($user);
			$verifier = OauthGetVerifier($token);
			OauthSetCredentialsStep2($token, $verifier, $authorized, $sessionId);	

			if ($authorized) {
				$this->ses['response']['redirect'] = OauthGetCallbackFromToken($token)."?oauth_token=".$token."&oauth_verifier=".$verifier;
			}
			else {
				$this->ses['response']['view'] = $XCOW_B['view_base'].'/base/oauth/authorize_no.php';
			}
		}
		else {
			$clientInfo = OauthGetClientFromToken($token);

			$this->ses['response']['param']['clientName'] = $clientInfo['name'];
			$this->ses['response']['param']['clientDescription'] = $clientInfo['description'];
			$this->ses['response']['param']['token'] = $token;

			$this->ses['response']['param']['error'] = "Gebruikersnaam en/of wachtwoord onjuist";
		}
	}
	else {
	}

    }

}

?>
