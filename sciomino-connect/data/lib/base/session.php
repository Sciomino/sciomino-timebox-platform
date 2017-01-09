<?php

#
# note: all session functions work on a 'per user' basis. 
#       it is not possible to retrieve lists of users based on methods in this file
#       use your own user file when making selections
#

function getSessionCount() {
        global $XCOW_B;

        $result = mysql_query("SELECT count(SessionId) FROM Session", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

#
# user id
#
function getUserIdFromUserName($user) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionId FROM Session WHERE SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function getUserIdFromEmail($mail) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionId FROM Session WHERE SessionEmail = '".safeInsert($mail)."'", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

#
# user name
#
function getUserNameFromUserId($sesId) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionUser FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function getUserNameFromKey($key) {
        global $XCOW_B;

	$user = NULL;

	$result = mysql_query("SELECT SessionUser FROM Session Where SessionKey = '$key'", $XCOW_B['mysql_link']);

	if (mysql_num_rows($result) == 1) {
		$result_row = mysql_fetch_assoc($result);
		$user = $result_row['SessionUser'];
	}

	return ($user);
}

function getUserNameFromEmail($mail) {
        global $XCOW_B;

	$user = NULL;

    	if (isEmail($mail)) {
		$result = mysql_query("SELECT SessionUser FROM Session Where SessionEmail = '".safeInsert($mail)."'", $XCOW_B['mysql_link']);

		if (mysql_num_rows($result) == 1) {
			$result_row = mysql_fetch_assoc($result);
			$user = $result_row['SessionUser'];
		}
        }

	return ($user);
}

#
# user email
#
function getUserEmailFromUserId($sesId) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionEmail FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

#
# displayName
#
function getUserDisplayNameFromUserId($sesId) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionDisplay FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

#
# user key
#
function getUserKeyFromUserId($sesId) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionKey FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

#
# create time
#
function getCreatetimeFromUserId($sesId) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionCreated FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

#
# login
#
function checkLogin($user, $pass) {
        global $XCOW_B;

	$md5Pass = md5($pass);

        $AuthResult = mysql_query("SELECT SessionPass, SessionActive FROM Session Where SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);

	$AuthResult_row = mysql_fetch_row($AuthResult);

        #
        # validate 
	#- session must be active
	#- password must not be empty
	#- password must match md5pass
        #
	if ($AuthResult_row[1] != 0 && (($AuthResult_row[0] != '') && ($AuthResult_row[0] == $md5Pass))) {
		return 1;
        }
        #
        # wrong username and/or password
        #
        else {
                return 0;
        }

}

#
# Session
#
function isActiveFromUserId($sesId) {
        global $XCOW_B;

        $result = mysql_query("SELECT SessionActive FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function activateSession($user) {

	global $XCOW_B;

        $result = mysql_query("UPDATE Session SET SessionActive = 1 WHERE SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);
}

function deactivateSession($user) {

	global $XCOW_B;

        $result = mysql_query("UPDATE Session SET SessionActive = 0 WHERE SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);
}

function startSession($session, $user) {
        global $XCOW_B;

		$timestamp = time();
        $addr = $session['request']['remote_addr'];
        $agent = mysql_real_escape_string(substr($session['request']['http_user_agent'],0,96));

        session_save_path($XCOW_B['session_save_path']);
        session_name($XCOW_B['session_name']);
        session_start();

        # use this against session hijacking
        session_regenerate_id(true);

        $AuthId = session_id();
        $AuthResult = mysql_query("UPDATE Session SET SessionKey = '$AuthId', SessionTimestamp = '$timestamp', SessionIpAddress = '$addr', SessionUserAgent = '$agent' WHERE SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);

		$_SESSION['ControlSessionKey']  = $AuthId;
		if ($XCOW_B['session_keep'] == 1) {
			setcookie(session_name()."_keep", session_id(), time()+(60*60*24*30), '/', $XCOW_B['session_cookie_domain']);
		}

		return 1;
}

function stopSession($id) {
        global $XCOW_B;

        session_save_path($XCOW_B['session_save_path']);
		session_name($XCOW_B['session_name']);
        session_start();

		# remove session variables
		$_SESSION = array();
	  
		# unset cookie in browser
        setcookie(session_name(), session_id(), 1, '/', $XCOW_B['session_cookie_domain']);
        setcookie(session_name(), session_id(), 1, '/');
		if ($XCOW_B['session_keep'] == 1) {
			setcookie(session_name()."_keep", session_id(), 1, '/', $XCOW_B['session_cookie_domain']);
		}

		# destroy :-)
		session_destroy();

        $AuthResult = mysql_query("UPDATE Session SET SessionKey = 'NULL' WHERE SessionId = $id", $XCOW_B['mysql_link']);

		return 1;
}

#
# REGISTER
#
function registerAttempt($user, $pass, $mail) {
        global $XCOW_B;

	$status = NULL;

	log2file("REGISTER ATTEMPT: user=".$user.", pass=***, mail=".$mail);

	//
	// Check user, pass & mail
	//
	$result = mysql_query("SELECT SessionId FROM Session Where SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);
	if (mysql_num_rows($result) == 1) {
		$status = "session_status_register_nameexists";
        }
	elseif (! isValidUser($user)) {
		$status = "session_status_register_namewrong";
 	}
	elseif (! isValidPass($pass)) {
		$status = "session_status_register_passwrong";
	}

	if (! isset($status)) {
	    	$result = mysql_query("SELECT SessionId FROM Session Where SessionEmail = '".safeInsert($mail)."'", $XCOW_B['mysql_link']);
	    	if (mysql_num_rows($result) != 0) {
			$status = "session_status_register_emailexists";
             	}
	    	elseif (! isEmail($mail)) {
			$status = "session_status_register_emailwrong";
            	}
	    	elseif ($XCOW_B['session_activate_domains']) {
			if (($pos = strpos($mail, '@')) !== false) {
				$domain = substr($mail, $pos + 1);
			    	if (! in_array($domain, $XCOW_B['session_valid_domains'])) {
					$status = "session_status_register_emailnotallowed";
			    	}			
			}
            	}
        }

	return $status;
}

function registerActivate($user, $pass, $mail) {

	global $XCOW_B;

	$session_id = 0;

	$random = md5(microtime().date("r").mt_rand(11111, 99999));
	$timestamp = time();

	log2file("REGISTER SUCCES: user=".$user.", pass=***, mail=".$mail);

	$result = mysql_query("INSERT INTO Session VALUES(NULL, '".safeInsert($user)."', '$pass', '".safeInsert($mail)."', '', '', '$random', 3, 0, '$timestamp', '0', '0', '0')", $XCOW_B['mysql_link']);
	// $session_id = mysql_insert_id($XCOW_B['mysql_link']);

	return $random;
}

function registerDelete($sesId) {

	global $XCOW_B;

	log2file("REGISTER DELETE: ses=".$sesId);

	$result = mysql_query("DELETE FROM Session WHERE SessionId = $sesId", $XCOW_B['mysql_link']);

	return 1;
}

#
# PASSWORD
#
function updatePassword($user, $pass) {

	global $XCOW_B;

	$md5Pass = md5($pass);

        $result = mysql_query("UPDATE Session SET SessionPass = '$md5Pass' WHERE SessionUser = '".safeInsert($user)."'", $XCOW_B['mysql_link']);

}

#
# REMOTE AUTHENTICATION
#
function updateRemoteAuthentication($id, $remoteUser) {

	global $XCOW_B;

        $result = mysql_query("UPDATE Session SET SessionRemoteUser = '".safeInsert($remoteUser)."' WHERE SessionId = $id", $XCOW_B['mysql_link']);

}

function updateDisplayName($id, $displayName) {

	global $XCOW_B;

        $result = mysql_query("UPDATE Session SET SessionDisplay = '".safeInsert($displayName)."' WHERE SessionId = $id", $XCOW_B['mysql_link']);

}

#
# ANONYMOUS
#
function getAnonymousIdFromKey($key) {
        global $XCOW_B;

	$id = 0;

	$result = mysql_query("SELECT SessionAnonymousId FROM SessionAnonymous Where SessionAnonymousKey = '$key'", $XCOW_B['mysql_link']);

	if (mysql_num_rows($result) == 1) {
		$result_row = mysql_fetch_assoc($result);
		$id = $result_row['SessionAnonymousId'];
	}

	return ($id);
}

function startAnonymousSession($session, $key) {
        global $XCOW_B;

	$session_id = 0;

	$timestamp = time();
        $addr = $session['request']['remote_addr'];
        $agent = $session['request']['http_user_agent'];

	$result = mysql_query("INSERT INTO SessionAnonymous VALUES(NULL, '$key', '$timestamp', '$timestamp', '$addr', '$agent')", $XCOW_B['mysql_link']);
	$session_id = mysql_insert_id($XCOW_B['mysql_link']);

	return $session_id;
}

?>
