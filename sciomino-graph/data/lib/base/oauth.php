<?php

# MISC

#
# Oauth Response
#
function OauthClientGetResponse($user, $app, $url, $method, $headers, $params) {
        global $XCOW_B;

	// setup
	// - get credentials from db
	// - create consumer & token
	// - create signature
	// - create & sign request
	$credentials = OauthClientGetCredentials($user, $app);
	$consumer = new OAuthConsumer($XCOW_B['oauth_client'][$app]['appKey'], $XCOW_B['oauth_client'][$app]['appSecret'], NULL);
	$token = new OAuthToken($credentials['token'], $credentials['secret']);
	if ($XCOW_B['oauth_client'][$app]['signatureMethod'] == 'HMAC') {
		$signature = new OAuthSignatureMethod_HMAC_SHA1();
	}
	else {
		$signature = new OAuthSignatureMethod_PLAINTEXT();
	}

	// create request, don't forget to put in the params
	$echo_req = OAuthRequest::from_consumer_and_token($consumer, $token, $method, $url, $params);
	$echo_req->sign_request($signature, $consumer, $token);
	//print_r($echo_req);
	//echo "request url: ".$echo_req->to_url()."\n";

	// go for it
	if ($method == "GET" ) {
		// authorization in url, params in url
		$echo_url = $echo_req->to_url();

		return getResponse($echo_url);
	}
	if ($method == "POST") {
		// 1. authorization in headers, don't touch $params
		$headers[] = $echo_req->to_header();
		// or 2. authorization in POST $params, don't touch $headers
		//$params = $echo_req->get_parameters();
		// or 3. authorization in query string (dat kan niet...)
		// $echo_url = $echo_req->to_url();

		$echo_url = $echo_req->get_normalized_http_url();
		$responseArray = postResponse($echo_url, $headers, $params);
		return $responseArray[1];
	}
}

#
# Oauth Get Connections
#
function OauthClientGetConnections($sessionId) {
        global $XCOW_B;

	$connections = array();

        $result = mysql_query("SELECT SessionConnectorId, SessionConnectorApp, SessionConnectorToken, SessionConnectorSecret, SessionConnectorTimestamp, SessionConnectorReference FROM SessionConnector WHERE SessionId = $sessionId AND SessionConnectorType = 'access'", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

                 	$connectionId = $result_row['SessionConnectorId'];

                	$connections[$connectionId] = array();
			$connections[$connectionId]['id'] = $result_row['SessionConnectorId'];
			$connections[$connectionId]['app'] = $result_row['SessionConnectorApp'];
			$connections[$connectionId]['token'] = $result_row['SessionConnectorToken'];
			$connections[$connectionId]['secret'] = $result_row['SessionConnectorSecret'];
			$connections[$connectionId]['timestamp'] = $result_row['SessionConnectorTimestamp'];
			$connections[$connectionId]['reference'] = $result_row['SessionConnectorReference'];
       		}
        }

	return $connections;
}

function OauthClientGetAllConnectionsBySession() {
        global $XCOW_B;

	$connections = array();

        $result = mysql_query("SELECT SessionConnectorId, SessionConnectorApp, SessionConnectorReference, SessionId FROM SessionConnector WHERE SessionConnectorType = 'access'", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

			$sessionId = $result_row['SessionId'];
			if (! is_array($connections[$sessionId])) {
				$connections[$sessionId] = array();
			}

                 	$connectionId = $result_row['SessionConnectorId'];
                	$connections[$sessionId][$connectionId] = array();
			$connections[$sessionId][$connectionId]['id'] = $result_row['SessionConnectorId'];
			$connections[$sessionId][$connectionId]['app'] = $result_row['SessionConnectorApp'];
			$connections[$sessionId][$connectionId]['reference'] = $result_row['SessionConnectorReference'];
       		}
        }

	return $connections;
}

#
# Oauth Update Reference
#
function OauthClientUpdateReference($credentialId, $reference) {
        global $XCOW_B;

	$result = mysql_query("UPDATE SessionConnector SET SessionConnectorReference = '$reference' WHERE SessionConnectorId = $credentialId", $XCOW_B['mysql_link']);

	return 1;
}


# CLIENT

#
# Oauth Get CredentialId
#
function OauthClientGetCredentialId($sessionId, $app) {
        global $XCOW_B;

	$credentialId = 0;

        $result = mysql_query("SELECT SessionConnectorId FROM SessionConnector WHERE SessionId = $sessionId AND SessionConnectorApp = '$app'", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
	        $result_row = mysql_fetch_row($result);
		$credentialId = $result_row[0];
        }

	return $credentialId;
}

#
# Oauth Get Credentials
#
function OauthClientGetCredentials($sessionId, $app) {
        global $XCOW_B;

	$credentials = array();

        $result = mysql_query("SELECT SessionConnectorType, SessionConnectorToken, SessionConnectorSecret FROM SessionConnector WHERE SessionId = $sessionId AND SessionConnectorApp = '$app'", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
	        $result_row = mysql_fetch_row($result);
		$credentials['type'] = $result_row[0];
		$credentials['token'] = $result_row[1];
		$credentials['secret'] = $result_row[2];
        }

	return $credentials;
}

#
# Oauth Set Credentials
#
function OauthClientSetCredentials($sessionId, $app, $credentials) {
        global $XCOW_B;

	$timestamp = time();

	$result = mysql_query("INSERT INTO SessionConnector VALUES(NULL, '$app', '${credentials['type']}', '${credentials['token']}', '${credentials['secret']}', '$timestamp', '', $sessionId)", $XCOW_B['mysql_link']);

	if ($result) {
		return mysql_insert_id($XCOW_B['mysql_link']);
	}
	else {
		return 0;
	}
}

#
# Oauth Update Credentials
#
function OauthClientUpdateCredentials($credentialId, $credentials) {
        global $XCOW_B;

	$timestamp = time();

	$result = mysql_query("UPDATE SessionConnector SET SessionConnectorType = '${credentials['type']}', SessionConnectorToken = '${credentials['token']}', SessionConnectorSecret = '${credentials['secret']}', SessionConnectorTimestamp = '$timestamp' WHERE SessionConnectorId = $credentialId", $XCOW_B['mysql_link']);

	return 1;
}

#
# Oauth Delete Credentials
#
function OauthClientDeleteCredentials($credentialId) {
        global $XCOW_B;

	$result = mysql_query("DELETE FROM SessionConnector WHERE SessionConnectorId = $credentialId", $XCOW_B['mysql_link']);

	return 1;
}

# SERVER

##########
# STEP 1 #
##########

# Oauth Check Consumer
#
function OauthCheckConsumer($consumer, $signature, $method) {
        global $XCOW_B;

        $result = mysql_query("SELECT OauthClientId, OauthClientSecret FROM OauthClient WHERE OauthClientKey = $consumer", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
	        $result_row = mysql_fetch_row($result);
		if ($method == "plain") {
			# plain signature is: ClientSecret
			if ($signature == $result_row[1]) {
				return $result_row[0];
			}
			else {
				return 0;
			}
		}                
        }
	else {
                return 0;
        }

}

#
# Oauth Check Callback
#
function OauthCheckCallback($callback) {

	# do html check (responsibility from caller)
        if ($callback != "") {
		return 1;
        }
	else {
                return 0;
        }

}

#
# Oauth Get Credentials
#
function OauthGetCredentials($consumerId) {

	$credentials = array();

	$credentials['token'] = md5($consumerId.microtime().date("r").mt_rand(11111, 99999));
	$credentials['secret'] = md5($consumerId.microtime().date("r").mt_rand(11111, 99999));

	return $credentials;

}

#
# Oauth Set Credentials Step 1
#
function OauthSetCredentialsStep1($consumerId, $callback, $credentials) {
        global $XCOW_B;

	$timestamp = time();

	$result = mysql_query("INSERT INTO OauthAccess VALUES(NULL, '${credentials['token']}', '${credentials['secret']}', '$timestamp', '$callback', '', 0, '', '', '', 1, '', $consumerId)", $XCOW_B['mysql_link']);

	if ($result) {
		return mysql_insert_id($XCOW_B['mysql_link']);
	}
	else {
		return 0;
	}

}

##########
# STEP 2 #
##########

#
# Oauth Get Client From Token
#
function OauthGetClientFromToken($token) {
        global $XCOW_B;

	$clientInfo = array();

       $result = mysql_query("SELECT OauthClientName, OauthClientDescription FROM OauthClient, OauthAccess WHERE OauthClient.OauthClientId = OauthAccess.OauthClientId AND OauthAccess.OauthAccessTempToken = '$token'", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
	        $result_row = mysql_fetch_row($result);
		$clientInfo['name'] = $result_row[0];
		$clientInfo['description'] = $result_row[1];
        }

	return $clientInfo;
}

#
# Oauth Get Callback From Token
#
function OauthGetCallbackFromToken($token) {
        global $XCOW_B;

	$callback = "";

        $result = mysql_query("SELECT OauthAccessCallback FROM OauthAccess WHERE OauthAccessTempToken = '$token'", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
	        $result_row = mysql_fetch_row($result);
		$callback = $result_row[0];
        }

	return $callback;
}

#
# Oauth Get Verifier
#
function OauthGetVerifier($token) {

	return md5($token.microtime().date("r").mt_rand(11111, 99999));

}

#
# Oauth Set Credentials Step 2
#
function OauthSetCredentialsStep2($token, $verifier, $authorized, $userId) {
        global $XCOW_B;

	$result = mysql_query("UPDATE OauthAccess SET OauthAccessVerifier = '$verifier', OauthAccessAuthorized = $authorized, OauthAccessStatus = 2, UserId = $userId WHERE OauthAccessTempToken = '$token'", $XCOW_B['mysql_link']);

	if ($result) {
		return mysql_insert_id($XCOW_B['mysql_link']);
	}
	else {
		return 0;
	}

}

##########
# STEP 3 #
##########

#
# Oauth Check Consumer Again
#
function OauthCheckConsumerAgain($consumer, $signature, $method, $token) {
        global $XCOW_B;

        $result = mysql_query("SELECT OauthClient.OauthClientId, OauthClient.OauthClientSecret, OauthAccess.OauthAccessTempSecret FROM OauthClient, OauthAccess WHERE OauthClientKey = $consumer AND OauthClient.OauthClientId = OauthAccess.OauthClientId AND OauthAccess.OauthAccessTempToken = '$token'", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
	        $result_row = mysql_fetch_row($result);
		if ($method == "plain") {
			# plain signature is: ClientSecret&TokenSecret
			if ($signature == $result_row[1]."&".$result_row[2]) {
				return $result_row[0];
			}
			else {
				return 0;
			}
		}                
        }
	else {
                return 0;
        }

}

#
# Oauth Check Verifier
#
function OauthCheckVerifier($verifier, $token) {
        global $XCOW_B;

        $result = mysql_query("SELECT OauthAccessId FROM OauthAccess WHERE OauthAccessTempToken = '$token' AND OauthAccessVerifier = '$verifier' AND OauthAccessAuthorized = 1", $XCOW_B['mysql_link']);

        if ($result && mysql_num_rows($result) == 1) {
		return 1;
   	}
	else {
                return 0;
        }

}

#
# Oauth Set Credentials Step 3
#
function OauthSetCredentialsStep3($token, $credentials) {
        global $XCOW_B;

	$timestamp = time();

	$result = mysql_query("UPDATE OauthAccess SET OauthAccessToken = '${credentials['token']}', OauthAccessSecret = '${credentials['secret']}', OauthAccessTimestamp = '$timestamp', OauthAccessStatus = 3 WHERE OauthAccessTempToken = '$token'", $XCOW_B['mysql_link']);

	if ($result) {
		return mysql_insert_id($XCOW_B['mysql_link']);
	}
	else {
		return 0;
	}

}

?>
