<?php

class oauthConnect extends control {

    function Run() {

        global $XCOW_B;
        
	$DEBUG_OAUTH = 0;

	$id = $this->ses['id'];

	$this->app = $this->ses['request']['param']['app'];
	$this->action = $this->ses['request']['param']['action'];

	// token & verifier are input to step 2
	$this->oauth_token = $this->ses['request']['param']['oauth_token'];
	$this->oauth_verifier = $this->ses['request']['param']['oauth_verifier'];

	// where to go after success?
	$this->callback = 1;
	$this->redirect = $XCOW_B['url']."/setting/connect";
	$this->redirect_source = $this->ses['request']['param']['source'];

	// setup
	if ($this->app) {
		$consumer = new OAuthConsumer($XCOW_B['oauth_client'][$this->app]['appKey'], $XCOW_B['oauth_client'][$this->app]['appSecret'], NULL);
	}
	else {
		$this->status = "no app";
	}

	$token = NULL;
	if ($this->oauth_token) {
		// use stored secret from step 1
		// TODO: check credentials type & token
		$credentials = OauthClientGetCredentials($id, $this->app);
		$token = new OAuthToken($this->oauth_token, $credentials['secret']);
	}
	else {
		$this->status = "no token";
	}

	if ($XCOW_B['oauth_client'][$this->app]['signatureMethod'] == 'HMAC') {
		$signature = new OAuthSignatureMethod_HMAC_SHA1();
	}
	else {
		$signature = new OAuthSignatureMethod_PLAINTEXT();
	}

	//
	// go for it
	//

	// step 1
	if ($this->action == "request") {
		$params = array();
		$addSource = "";
		if (isset($this->redirect_source)) {$addSource = "&source=".$this->redirect_source;}
		$params['oauth_callback'] = $XCOW_B['oauth_client'][$this->app]['callbackUrl'].$addSource;
		//$parsed = parse_url($this->appUrl);
		//parse_str($parsed['query'], $params);

  		$req_req = OAuthRequest::from_consumer_and_token($consumer, NULL, $XCOW_B['oauth_client'][$this->app]['appMethod'], $XCOW_B['oauth_client'][$this->app]['appUrl'].$XCOW_B['oauth_client'][$this->app]['requestPath'], $params);
  		$req_req->sign_request($signature, $consumer, NULL);
		if ($DEBUG_OAUTH) {
			$this->status = "request url: ".$req_req->to_url()."\n";
			print_r($req_req);
		}
		else {
			if ($XCOW_B['oauth_client'][$this->app]['appMethod'] == "GET" ) {
				$reqUrl = $req_req->to_url();
				$response = getResponse($reqUrl);
			}
			if ($XCOW_B['oauth_client'][$this->app]['appMethod'] == "POST") {
				$headers = array();
				$params = $req_req->get_parameters();
				$reqUrl = $req_req->get_normalized_http_url();
				$responseArray = postResponse($reqUrl, $headers, $params);
				$response = $responseArray[1];
			}

			$responseParams = array();
			parse_str($response, $responseParams);

			//store secret from step 1
			$credentials = array();
			$credentials['type'] = 'request';
			$credentials['token'] = $responseParams['oauth_token'];
			$credentials['secret'] = $responseParams['oauth_token_secret'];
			if (($credentialId = OauthClientGetCredentialId($id, $this->app)) == 0) {
				OauthClientSetCredentials($id, $this->app, $credentials);
			}
			else {
				OauthClientUpdateCredentials($credentialId, $credentials);
			}

			$this->ses['response']['redirect'] = $XCOW_B['oauth_client'][$this->app]['authorizationUrl']."?oauth_token=".$responseParams['oauth_token'];
 			//header ("Location: ".$XCOW_B['oauth_client'][$this->app]['authorizationUrl']."?oauth_token=".$responseParams['oauth_token']."\n\n");
		}

	}

	// step 2
	if ($this->action == "access") {
		$params = array();
		$params['oauth_verifier'] = $this->oauth_verifier;

		$acc_req = OAuthRequest::from_consumer_and_token($consumer, $token, $XCOW_B['oauth_client'][$this->app]['appMethod'], $XCOW_B['oauth_client'][$this->app]['appUrl'].$XCOW_B['oauth_client'][$this->app]['accessPath'], $params);
		$acc_req->sign_request($signature, $consumer, $token);
		if ($DEBUG_OAUTH) {
			$this->status = "request url: ".$acc_req->to_url()."\n";
			print_r($acc_req);
		}
		else {
			if ($XCOW_B['oauth_client'][$this->app]['appMethod'] == "GET" ) {
				$accUrl = $acc_req->to_url();
				$response = getResponse($accUrl);
			}
			if ($XCOW_B['oauth_client'][$this->app]['appMethod'] == "POST") {
				$headers = array();
				$params = $acc_req->get_parameters();
				$accUrl = $acc_req->get_normalized_http_url();
				$responseArray = postResponse($accUrl, $headers, $params);
				$response = $responseArray[1];
			}

			$responseParams = array();
			parse_str($response, $responseParams);

			//store secret from step 2
			$credentials = array();
			$credentials['type'] = 'access';
			$credentials['token'] = $responseParams['oauth_token'];
			$credentials['secret'] = $responseParams['oauth_token_secret'];
			if (($credentialId = OauthClientGetCredentialId($id, $this->app)) == 0) {
				OauthClientSetCredentials($id, $this->app, $credentials);
			}
			else {
				OauthClientUpdateCredentials($credentialId, $credentials);
			}
	
			if ($this->callback) {
				if (isset($this->redirect_source)) {
					$this->ses['response']['redirect'] = $XCOW_B['url'].$this->redirect_source;
				}
				else {
					$this->ses['response']['redirect'] = $this->redirect;
				}
			}
			else {
				$this->ses['response']['param']['status'] = "Connected!";
			}
		}
	}

	// disconnect
	if ($this->action == "invalidate") {

		//TODO: should tell app as well!

		//delete secret
		if (($credentialId = OauthClientGetCredentialId($id, $this->app)) != 0) {
			OauthClientDeleteCredentials($credentialId);
		}

		if ($this->callback) {
			if (isset($this->redirect_source)) {
				$this->ses['response']['redirect'] = $XCOW_B['url'].$this->redirect_source;
			}
			else {
				$this->ses['response']['redirect'] = $this->redirect;
			}
		}
		else {
			$this->ses['response']['param']['status'] = "Disconnected!";
		}

	}

     }

}

?>
