<?

class OauthInitiate extends control {

    function Run() {

	$oauth_credentials = array();
	$oauth_consumer_id = 0;
	$oauth_callback_ok = 0;

	$oauth_consumer = $this->ses['request']['param']['oauth_consumer_key'];
	$oauth_signature = $this->ses['request']['param']['oauth_signature'];
	$oauth_method = $this->ses['request']['param']['oauth_signature_method'];
	$oauth_callback = $this->ses['request']['param']['oauth_callback'];

echo "1:".$oauth_consumer;
echo "2:".$oauth_signature;
echo "3:".$oauth_method;
echo "4:".$oauth_callback;

	$oauth_consumer_id = OauthCheckConsumer($oauth_consumer, $oauth_signature, $oauth_method);
	$oauth_callback_ok = OauthCheckCallback($oauth_callback);

echo "5:".$oauth_consumer_id;
echo "6:".$oauth_callback_ok;

	if ($oauth_consumer_id && $oauth_callback_ok) {
		$oauth_credentials = OauthGetCredentials($oauth_consumer_id);
		OauthSetCredentialsStep1($oauth_consumer_id, $oauth_callback, $oauth_credentials);
		$this->ses['response']['param']['credentials'] = "oauth_token=".$oauth_credentials['token']."&oauth_token_secret=".$oauth_credentials['secret']."&oauth_callback_confirmed=TRUE";
	}
	else {	
		$this->ses['response']['param']['credentials'] = "Access Denied";

	}

    }
}

?>
