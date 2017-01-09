<?

class OauthToken extends control {

    function Run() {

	$oauth_credentials = array();
	$oauth_consumer_id = 0;
	$oauth_callback_ok = 0;

	$oauth_consumer = $this->ses['request']['param']['oauth_consumer_key'];
	$oauth_signature = $this->ses['request']['param']['oauth_signature'];
	$oauth_method = $this->ses['request']['param']['oauth_signature_method'];
	$oauth_token = $this->ses['request']['param']['oauth_token'];
	$oauth_verifier = $this->ses['request']['param']['oauth_verifier'];

echo "1:".$oauth_consumer;
echo "2:".$oauth_signature;
echo "3:".$oauth_method;
echo "4:".$oauth_token;
echo "5:".$oauth_verifier;

	$oauth_consumer_id = OauthCheckConsumerAgain($oauth_consumer, $oauth_signature, $oauth_method, $oauth_token);
	$oauth_verifier_ok = OauthCheckVerifier($oauth_verifier, $oauth_token);

echo "6:".$oauth_consumer_id;
echo "7:".$oauth_verifier_ok;

	if ($oauth_consumer_id && $oauth_verifier_ok) {
		$oauth_credentials = OauthGetCredentials($oauth_consumer_id);
		OauthSetCredentialsStep3($oauth_token, $oauth_credentials);
		$this->ses['response']['param']['credentials'] = "oauth_token=".$oauth_credentials['token']."&oauth_token_secret=".$oauth_credentials['secret'];
	}
	else {	
		$this->ses['response']['param']['credentials'] = "Access Denied";

	}

    }
}

?>
