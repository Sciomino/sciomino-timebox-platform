<?php

# steps to take:
#  1 load libraries
#  2 build datastructure
#  3 analyse url
#  4 read XML params
#  5 controller match
#  6 authorization check
#  7 session handling
#  8 call model
#  9 call extensions
# 10 call view
# 11 clean up

#
# 1. LOAD LIBRARIES
#

ini_set('display_errors', false);
#error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE);

# 1a. globals
require $_SERVER['DOCUMENT_ROOT']."/../data/etc/config.php";

# 1b. libraries
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/libs.php";

# 1c. utils
require $_SERVER['DOCUMENT_ROOT']."/../data/model/utils.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/view/utils.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/extension/utils.php";

# 1d. controller
require $_SERVER['DOCUMENT_ROOT']."/../data/control/ini.php";

#set_time_limit(300);

# open log file
openLogFile();

#
# 2. BUILD DATASTRUCTURE
#

# session
#         -> request
#		     -> server name
#		     -> https
#                    -> script_name
#                    -> path_info
#                    -> path_original
#                    -> path_translated
#                    -> request_method
#                    -> query_string
#                    -> remote_addr
#                    -> x_forwarder_for (not used)
#                    -> user agent
#                    -> referer
#                    -> authorization
#                    -> param
#                    -> raw
#                    -> raw headers
#                    -> file_info
#                    -> REST
#                    	-> method
#                    	-> object
#                    	-> param
#                    	-> function
#                    -> language
#         -> response
#                    -> model
#                    -> class
#                    -> view
#                    -> anonymous
#                    -> access
#                    -> param
#                    -> extension
#                    -> extension_class
#                    -> extension_param
#                    -> header
#                    -> redirect
#		     -> stats
#		     -> language
#         -> id
#         -> time
#

$request  = array();
$response = array();
$session  = array();

$response['model']           = '';
$response['class']           = '';
$response['view']            = '';
$response['anonymous']       = 0;
$response['database']        = 0;
$response['access']          = 0;
$response['param']           = array();
$response['extension']       = '';
$response['extension_class'] = '';
$response['extension_param'] = array();
$response['header']          = '';
$response['redirect']        = '';
$response['stats']	     = array();
$response['language']        = '';

$request['server_name']      = $_SERVER['SERVER_NAME'];
$request['https']            = $_SERVER['HTTPS'];
$request['script_name']      = $_SERVER['SCRIPT_NAME'];
$request['path_info']        = $_SERVER['PATH_INFO'];
$request['path_original']    = $_SERVER['PATH_INFO'];
$request['path_translated']  = $_SERVER['PATH_TRANSLATED'];
$request['request_method']   = $_SERVER['REQUEST_METHOD'];
$request['query_string']     = $_SERVER['QUERY_STRING'];
$request['remote_addr']      = $_SERVER['REMOTE_ADDR'];
# hum, doe nog niks met: $_SERVER['HTTP_X_FORWARDED_FOR']
$request['http_user_agent']  = $_SERVER['HTTP_USER_AGENT'];
$request['http_referer']     = $_SERVER['HTTP_REFERER'];
$request['authorization']    = $_SERVER['AUTHORIZATION'];
$request['user']	     = $_SERVER['REMOTE_USER'];
$request['param']            = $_REQUEST;
$request['REST']             = array();
#$request['raw']              = $HTTP_RAW_POST_DATA;
$request['raw']              = file_get_contents('php://input'); 
$request['raw_headers']      = apache_request_headers();
#print_r ($request['raw_headers']);
$request['file_info']        = $_FILES;
$request['language']         = '';
$request['DB']         	     = array();

$session['request']          = $request;
$session['response']         = $response;

#
# 3. ANALYSE URL
# - http://SITE/PATH/LANGUAGE/control
#

# 3a. remove trailing '/PATH' and  possible '/' from path_info;
$session['request']['path_info'] = preg_replace ("/^".preg_quote($XCOW_B['url'], "/")."(\/*)(.*)$/", "\$2", $session['request']['path_info']);

# 3b. Language

# language is the first part of the URL 
# - http://SITE/nl/web/home <= language = nl
# - http://SITE/web/home <= language = default
# first, use language from url
# second, use language from cookie
# third, use default language
# TODO: fourth, if logged in, override language from session database.
$session['request']['language'] = substr($session['request']['path_info'], 0, 2);
if (in_array($session['request']['language'], $XCOW_B['valid_languages']) && (substr($session['request']['path_info'], 2, 1) == "/" || $session['request']['path_info'] == substr($session['request']['path_info'], 0, 2)) ) {
	if (strlen($session['request']['path_info']) <= 3) {
		$session['request']['path_info'] = "";
	}
	else {
		$session['request']['path_info'] = substr($session['request']['path_info'], 3);
	}
}
else {
	if (isset($_COOKIE[$XCOW_B['language_cookie_name']]) && in_array($_COOKIE[$XCOW_B['language_cookie_name']], $XCOW_B['valid_languages'])) {
		$session['request']['language'] = $_COOKIE[$XCOW_B['language_cookie_name']];
	}
	else {
		$session['request']['language'] = $XCOW_B['default_language'];
	}
}
setcookie($XCOW_B['language_cookie_name'], $session['request']['language'], time()+(60*60*24*365), "/", $XCOW_B['session_cookie_domain']);

# 3c. rewrite
#
$rewrite = rewrite($session['request']['path_info'], $session['request']['param']);
if ($rewrite['match']) {
	$session['request']['path_info'] = $rewrite['url'];
	$session['request']['param'] = $session['request']['param'] + $rewrite['param'];
}

# 3d. REST REQUEST
 
# example: GET video/list (method object/function)
# example: POST video/1/update (method object/param/function)
if ($XCOW_B['RESTmode']) {

    $session['request']['REST']['method']   = $session['request']['request_method'];
    $session['request']['REST']['object']   = $session['request']['path_info'];
    $session['request']['REST']['param']    = NULL;
    $session['request']['REST']['function'] = NULL;

    $path_info_array = explode ('/', $session['request']['path_info']);
    if (count($path_info_array) == 2) {
        $session['request']['REST']['object']   = $path_info_array[0]; 
        $session['request']['REST']['function'] = $path_info_array[1]; 

        # pass object_function to the controller
        $session['request']['path_info'] = $session['request']['REST']['object']."/".$session['request']['REST']['function'];
    }
    elseif (count($path_info_array) == 3) {
        $session['request']['REST']['object']   = $path_info_array[0]; 
        $session['request']['REST']['param']    = $path_info_array[1]; 
        $session['request']['REST']['function'] = $path_info_array[2]; 

        # pass object_function to the controller
        $session['request']['path_info'] = $session['request']['REST']['object']."/".$session['request']['REST']['function'];
    } 

}

#
# 4. READ XML PARAMS
#

# xml input
if ($XCOW_B['XMLmode'] && $session['request']['raw']) {
    $controlXmlObj = new Xml2Php2($session['request']['raw'], 1);

    $controlXmlArray = $controlXmlObj->getPhpArray();
    $session['request']['param'] = $controlXmlArray['param'];

}

#
# 5. CONTROLLER MATCH
#

# an element is found!
# - check if rewrite api is the only entrance
if ( array_key_exists($session['request']['path_info'], $XCOW_B['control']) && !($rewrite['match']==0 && $XCOW_B['rewrite_only']==1) ) {

    $controlElement = $XCOW_B['control'][$session['request']['path_info']];

    $session['response']['model']  = $controlElement['model'];
    $session['response']['class']  = $controlElement['class'];
    $session['response']['view']   = $controlElement['view'];
    $session['response']['anonymous'] = $controlElement['anonymous'];
    $session['response']['database'] = $controlElement['database'];
    $session['response']['access'] = $controlElement['access'];

    # 
    # look into parameters voor possible extensions
    #
    if (array_key_exists('param', $controlElement) ) {

        $controlParamName  = $controlElement['param'];
        $controlParamValue = $session['request']['param'][$controlParamName];

        if (array_key_exists($controlParamValue, $controlElement) ) {

            #
            # we found a specific access level
            #
            if (array_key_exists('access', $controlElement[$controlParamValue]) ) {

                $session['response']['access'] = $controlElement[$controlParamValue]['access'];

            }

            #
            # we found a specific extension
            #
            if (array_key_exists('extension', $controlElement[$controlParamValue]) ) {

                $session['response']['extension'] = $controlElement[$controlParamValue]['extension'];
                $session['response']['extension_class'] = $controlElement[$controlParamValue]['class'];

            }

        }

    }

    #
    # adjust access level to editor function
    #
    if (isset($session['request']['param']['editor']) ) {
       if ($session['response']['access'] < $XCOW_B['editor_access_level']) {
           $session['response']['access'] = $XCOW_B['editor_access_level'];
       }
    }

}
# no element in the control structure
else {

    $session['response']['model']  = $XCOW_B['control']['error404']['model'];
    $session['response']['class']  = $XCOW_B['control']['error404']['class'];
    $session['response']['view']   = $XCOW_B['control']['error404']['view'];
    $session['response']['anonymous'] = $XCOW_B['control']['error404']['anonymous'];
    $session['response']['database'] = $XCOW_B['control']['error404']['database'];
    $session['response']['access'] = $XCOW_B['control']['error404']['access'];

}

#
# 6. AUTHORIZATION CHECK
#
if ($XCOW_B['auth']['on']) {

	# get auth params
	$id_param = $XCOW_B['auth']['id'];
	$id = $session['request']['param'][$id_param];
	$nonce_param = $XCOW_B['auth']['nonce'];
	$nonce = $session['request']['param'][$nonce_param];
	$key_param = $XCOW_B['auth']['key'];
	$key = $session['request']['param'][$key_param];
	$db = $XCOW_B['auth']['db'];

        # clients with same auth parameters
        foreach ($XCOW_B['auth']['same_clients'] as $authClient) {
                $XCOW_B[$authClient]['id'] = $id;
                $XCOW_B[$authClient]['nonce'] = $nonce;
                $XCOW_B[$authClient]['key'] = $key;
        }

	# open db
	$XCOW_B[$db]['mysql_link'] = mysql_connect($XCOW_B[$db]['mysql_host'], $XCOW_B[$db]['mysql_user'], $XCOW_B[$db]['mysql_pass']);
	mysql_select_db($XCOW_B[$db]['mysql_db'], $XCOW_B[$db]['mysql_link']);
	mysql_query("SET NAMES 'utf8';", $XCOW_B[$db]['mysql_link']);

	# get secret
	$authResult = mysql_query("SELECT AuthAppSecret, AuthAppSuffix FROM AuthApp Where AuthAppName like '$id'", $XCOW_B[$db]['mysql_link']);
	$authResult_row = mysql_fetch_row($authResult);
	$secret = $authResult_row[0];
	$suffix = $authResult_row[1];

	# remember suffix
	$session['request']['DB']['suffix'] = $suffix;

	# create personal queue
	$XCOW_B['queue'] = $XCOW_B['queue']."/".$id;

	# close session db
	mysql_close($XCOW_B[$db]['mysql_link']);

	if ($key != sha1($nonce.$id.$secret)) {
		$session['response']['model']  = $XCOW_B['control']['error401']['model'];
		$session['response']['class']  = $XCOW_B['control']['error401']['class'];
		$session['response']['view']   = $XCOW_B['control']['error401']['view'];
		$session['response']['anonymous'] = $XCOW_B['control']['error401']['anonymous'];
		$session['response']['database'] = $XCOW_B['control']['error401']['database'];
		$session['response']['access'] = $XCOW_B['control']['error401']['access'];
	}
}

#
# 7. SESSION HANDLING if access needed
#
if ( $session['response']['access'] > 0 ) {

    session_save_path($XCOW_B['session_save_path']);
    session_name($XCOW_B['session_name']);
    # as long as the keep cookie exist, regenerate the same session
	if ($XCOW_B['session_keep'] == 1) {
		if (isset($_COOKIE[session_name()."_keep"])) {
			session_id($_COOKIE[session_name()."_keep"]);
		}
	}
    session_start();

    # open session db
    $XCOW_B['sessionDB']['mysql_link'] = mysql_connect($XCOW_B['sessionDB']['mysql_host'], $XCOW_B['sessionDB']['mysql_user'], $XCOW_B['sessionDB']['mysql_pass']);
    mysql_select_db($XCOW_B['sessionDB']['mysql_db'], $XCOW_B['sessionDB']['mysql_link']);
    mysql_query("SET NAMES 'utf8';", $XCOW_B['sessionDB']['mysql_link']);
    # hmm, voor remote user...
    $XCOW_B['mysql_link'] = $XCOW_B['sessionDB']['mysql_link'];

	# session could be closed on the server, regenerate the session value as well
	if (! isset($_SESSION['ControlSessionKey']) && $XCOW_B['session_keep'] == 1) {
		if (isset($_COOKIE[session_name()."_keep"])) {
			$_SESSION['ControlSessionKey'] = $_COOKIE[session_name()."_keep"];
		}
	}

    # session started
    if (isset($_SESSION['ControlSessionKey'])) {

    $controlKey = $_SESSION['ControlSessionKey'];

	session_write_close();

	#
	# decide which autorization to use: oauth or session
	#	
	$AUTHmode = "session";
	if (!isset ($request['authorization'])) {
		$request['authorization'] = $request['raw_headers']['Authorization'];
	}
	if (substr($request['authorization'],0,6) == 'OAuth ') {
		$AUTHmode = "oauth";
		$OauthParams = explode(',', substr($request['authorization'], strpos($request['authorization'], ' ')+1));
                $Oauth = array();
                foreach($OauthParams as $Okey => $Oval) {
                    $Oval = trim($Oval);
                    if(strpos($Oval,'=') !== false) {
                        $lhs = substr($Oval,0,strpos($Oval,'='));
                        $rhs = substr($Oval,strpos($Oval,'=')+1);
                        if(substr($rhs,0,1) == '"' && substr($rhs,-1,1) == '"') {
                            $rhs = substr($rhs,1,-1);
                        }
                        $Oauth[$lhs] = $rhs;
                    }
                }
	
	}

	#
	# Oauth
	#
	if ($AUTHmode == "oauth") {
		$OauthGranted = 0;
	        
		$controlResult = mysql_query("SELECT UserId, OauthAccessAuthorized, OauthAccessToken, OauthAccessSecret, OauthAccessStatus, OauthAccessTimestamp FROM OauthAccess Where OauthAccessToken = '{$Oauth['oauth_token']}'");
	        $controlResult_row = mysql_fetch_row($controlResult);
		
	      	$session['id'] = $controlResult_row[0];
	      	$session['time'] = $controlResult_row[5];

		# authorized = yes & status = step 3
		if ($controlResult_row[1] == 1 && $controlResult_row[4] == 3) { 
			
			# if signature matches then granted = 1
			if ($Oauth['oauth_signature_method'] == "PLAINTEXT") {
				$signatureMatch = $controlResult_row[3]."&".$controlResult_row[2];
				if ($Oauth['oauth_signature'] == $signatureMatch) {
					$OauthGranted = 1;
				}
			}

		}

	      	if (! $OauthGranted) {

	               	$session['response']['model']  = $XCOW_B['control']['error401']['model'];
	               	$session['response']['class']  = $XCOW_B['control']['error401']['class'];
	               	$session['response']['view']   = $XCOW_B['control']['error401']['view'];
	               	$session['response']['anonymous'] = $XCOW_B['control']['error401']['anonymous'];
			$session['response']['database'] = $XCOW_B['control']['error401']['database'];
	               	$session['response']['access'] = $XCOW_B['control']['error401']['access'];

		}
	}
	#
	# Session
	#
	else {
        	#
        	# anonymous login
        	#
        	if ($session['response']['anonymous']) {

        	    $controlResult = mysql_query("SELECT SessionAnonymousId, SessionAnonymousTimestamp FROM SessionAnonymous Where SessionAnonymousKey = '$controlKey'");
        	    $controlResult_row = mysql_fetch_row($controlResult);

	            $session['id'] = $controlResult_row[0];
		    $session['time'] = $controlResult_row[1];

		    # echo "SESSION: ".$session['id'];
	        }
	        else {
	            $controlResult = mysql_query("SELECT SessionID, SessionAccessLevel, SessionTimestamp FROM Session Where SessionKey = '$controlKey'");
	            $controlResult_row = mysql_fetch_row($controlResult);

	            $session['id'] = $controlResult_row[0];
	            $session['time'] = $controlResult_row[2];

	            if ($controlResult_row[1] < $session['response']['access']) {

	                $session['response']['model']  = $XCOW_B['control']['error401']['model'];
	                $session['response']['class']  = $XCOW_B['control']['error401']['class'];
	                $session['response']['view']   = $XCOW_B['control']['error401']['view'];
	                $session['response']['anonymous'] = $XCOW_B['control']['error401']['anonymous'];
					$session['response']['database'] = $XCOW_B['control']['error401']['database'];
					$session['response']['access'] = $XCOW_B['control']['error401']['access'];

                    // clear session cookie for future login
                    setcookie(session_name(), session_id(), 1, '/', $XCOW_B['session_cookie_domain']);
                    setcookie(session_name(), session_id(), 1, '/');
                   	if ($XCOW_B['session_keep'] == 1) {
						setcookie(session_name()."_keep", session_id(), 1, '/', $XCOW_B['session_cookie_domain']);
					}
                    $_SESSION = array();
	            }
	            else {
                    // keep session
 					if ($XCOW_B['session_keep'] == 1) {
						setcookie(session_name()."_keep", session_id(), time()+(60*60*24*30), '/', $XCOW_B['session_cookie_domain']);
					}
				}
	        }
	}

    }
    # new session
    else {

	if ($session['response']['access'] > 1) {

		# remote authentication
		if ($XCOW_B['session_remote_authentication']) {
			$remote_access = 1;

			# if not environment:REMOTE_USER try header:remote_user
			if ($XCOW_B['session_remote_auth_use_header'] && $session['request']['user'] == '') {
				$tryHeader = $XCOW_B['session_remote_auth_header'];
				$session['request']['user'] = $session['request']['raw_headers'][$tryHeader];
			}
			
			# strip user from user@domain
			if (($pos = strpos($session['request']['user'], '@')) !== false) {
				$session['request']['user'] = substr($session['request']['user'], 0, $pos);
			}
			
			# not an empty user please
			if ($session['request']['user'] == '') {
				$remote_access = 0;
			}
			else {
				# remote_user is exact match of SessionRemoteUser field, default behavior
				$controlResult = mysql_query("SELECT SessionID, SessionAccessLevel, SessionUser, SessionTimestamp FROM Session Where SessionRemoteUser = '{$session['request']['user']}'");
		    	if ($controlResult && mysql_num_rows($controlResult) != 0) {
					$controlResult_row = mysql_fetch_row($controlResult);
					$session['id'] = $controlResult_row[0];
					$session['time'] = $controlResult_row[3];
				}
				else {
					# try matching remote_user on SessionUser, second try
					# - optional trim int with leading zeros
					if ($XCOW_B['session_remote_auth_int_trim']) {
						$session['request']['user'] = ltrim($session['request']['user'], "0");
					}
					$controlResult = mysql_query("SELECT SessionID, SessionAccessLevel, SessionUser, SessionTimestamp FROM Session Where SessionUser = '{$session['request']['user']}'");
					if ($controlResult && mysql_num_rows($controlResult) != 0) {
						$controlResult_row = mysql_fetch_row($controlResult);
						$session['id'] = $controlResult_row[0];
						$session['time'] = $controlResult_row[3];
					}
					else {
						# not a match
						$remote_access = 0;
					}
				}
			}
			
			if ($remote_access == 0 || $controlResult_row[1] < $session['response']['access']) {
				# access denied, to the error screen
				$session['response']['model']  = $XCOW_B['control']['error401']['model'];
				$session['response']['class']  = $XCOW_B['control']['error401']['class'];
				$session['response']['view']   = $XCOW_B['control']['error401']['view'];
				$session['response']['anonymous'] = $XCOW_B['control']['error401']['anonymous'];
				$session['response']['database'] = $XCOW_B['control']['error401']['database'];
				$session['response']['access'] = $XCOW_B['control']['error401']['access'];

				// clear session cookie for future login
				setcookie(session_name(), session_id(), 1, '/', $XCOW_B['session_cookie_domain']);
				setcookie(session_name(), session_id(), 1, '/');
				$_SESSION = array();
				if ($XCOW_B['session_keep'] == 1) {
					setcookie(session_name()."_keep", session_id(), 1, '/', $XCOW_B['session_cookie_domain']);
				}

		    }
		    else {
				# access granted, go ahead, make my day
				startSession($session, $controlResult_row[2]);
		    }
		}
		
		# local authentication, to the login screen...
		else {
                        $session['response']['model']  = $XCOW_B['control']['web/login']['model'];
                        $session['response']['class']  = $XCOW_B['control']['web/login']['class'];
                        $session['response']['view']   = $XCOW_B['control']['web/login']['view'];
                        $session['response']['anonymous'] = $XCOW_B['control']['web/login']['anonymous'];
                        $session['response']['database'] = $XCOW_B['control']['web/login']['database'];
                        $session['response']['access'] = $XCOW_B['control']['web/login']['access'];
                        $session['request']['param']['callback'] = 1;
		}

	}

    }

    # close session db
    mysql_close($XCOW_B['sessionDB']['mysql_link']);

}

#
# 8. CALL MODEL and get parameters
#

# read language files
$session['response']['language'] = $session['request']['language'];
$language_directory = $_SERVER['DOCUMENT_ROOT']."/../data/language/".$session['response']['language'];
if ($openDir = opendir($language_directory)) {
        while (false !== ($file = readdir($openDir))) {
            // skip hidden files and . and .. directories
            if ($file[0]==".") {
                continue;
            }

            // add file
            if (is_file($language_directory."/".$file)) {
                require $language_directory."/".$file;
            }
        }
        closedir($openDir);
}

# Open database connection
if (! ($session['response']['database'] == "" || $session['response']['database'] == "none") ) {
	$db = $session['response']['database'];
	$XCOW_B['mysql_link'] = mysql_connect($XCOW_B[$db]['mysql_host'], $XCOW_B[$db]['mysql_user'], $XCOW_B[$db]['mysql_pass']);
	$dbFile = $XCOW_B[$db]['mysql_db'];
	if (isset($session['request']['DB']['suffix'])) {
		$dbFile .= $session['request']['DB']['suffix'];
	}
	mysql_select_db($dbFile, $XCOW_B['mysql_link']);
	mysql_query("SET NAMES 'utf8';", $XCOW_B['mysql_link']);
}

# strip slashes & tags
if ($XCOW_B['stripslashes']) {
        $session['request']['param'] = stripslashes_deep($session['request']['param']);
        $session['request']['param'] = stripslashes_keys($session['request']['param']);
}
if ($XCOW_B['striptags']) {
        $session['request']['param'] = striptags_deep($session['request']['param']);
        $session['request']['param'] = striptags_keys($session['request']['param']);
}

# go
require $session['response']['model'];

$controlObj = new $session['response']['class']($session);
$controlObj->Run();
$controlObj->Finish();

$session['response']['param']    = $controlObj->GetParam();
$session['response']['view']     = $controlObj->GetView();
$session['response']['header']   = $controlObj->GetHeader();
$session['response']['redirect'] = $controlObj->GetRedirect();
$session['response']['stats']    = $controlObj->GetStats();

# close database
if (! ($session['response']['database'] == "" || $session['response']['database'] == "none") ) {
	mysql_close($XCOW_B['mysql_link']);
}

#
# 9. CALL EXTENSIONS
#

if ($session['response']['extension'] != '') {

    require $session['response']['extension'];

    $controlObj2 = new $session['response']['extension_class']($session);
    $controlObj2->Run();

    $session['response']['extension_param']  = $controlObj2->GetExtensionParam();
}

#
# 10. CALL VIEW
#

# check header & go
if ($session['response']['header'] != '') {
    $controlHeader = $session['response']['header'];
    header ($controlHeader);

    include $session['response']['view'];
}
elseif ($session['response']['redirect'] != '') {
    $controlRedirect = $session['response']['redirect'];
    header ("Location: $controlRedirect\n\n");
}
else {
    include $session['response']['view'];
}

#
# 11. CLEAN UP
#

# log request to file
if ($XCOW_B['DEBUG'] > 0) {
    log2file("REQUEST".":remote_addr=".$session['request']['remote_addr'].":path_info=".$session['request']['path_info'].":query_string=".$session['request']['query_string'].":raw=".$session['request']['raw'].":model=".$session['response']['model'].":view=".$session['response']['view'].":extension=".$session['response']['extension'].":anonymous=".$session['response']['anonymous'].":database=".$session['response']['database'].":access=".$session['response']['access']);
}

# show environment if in DEBUG mode
if ($XCOW_B['DEBUG'] > 1) {

    echo "<h1>SESSION array</h1>";
    echo "<pre>";
    print_r ($session);
    echo "</pre>";
    
    echo "<h1>XCOW array</h1>";
    echo "<pre>";
    print_r ($XCOW_B);
    echo "</pre>";
}

#
# close session
#
session_write_close();

#
# close logfile
#
closeLogFile();

?>
