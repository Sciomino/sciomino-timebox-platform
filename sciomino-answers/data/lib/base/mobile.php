<?php

#
# Register & maintain a mobile session
#
# authorize => registerAttempt => registerInit
# token => registerActivate => SessionConnect
#
function mobileRegisterAttempt($app, $mail) {
    global $XCOW_B;

	$status = NULL;

	log2file("MOBILE REGISTER ATTEMPT: app=".$app.", mail=".$mail);

	//
	// Check valid app name
	// - note: multiple app/mail combinations are possible, you can download the app on phone AND tablet with the same email address
	//
	if (! in_array($app, $XCOW_B['valid_apps'])) {
		$status = "session_status_mobile_register_appnotallowed";
 	}

	// Mail must be valid
	if (! isset($status)) {
	    if (! isEmail($mail)) {
			$status = "session_status_mobile_register_emailwrong";
        }
	    elseif ($XCOW_B['session_activate_domains']) {
			if (($pos = strpos($mail, '@')) !== false) {
				$domain = substr($mail, $pos + 1);
		    	if (! in_array($domain, $XCOW_B['session_valid_domains'])) {
					$status = "session_status_mobile_register_emailnotallowed";
		    	}			
			}
        }
    }

	return $status;
}

function mobileRegisterInit($app, $mail, $secret) {

	global $XCOW_B;

	$random = md5($app.$mail.microtime().date("r").mt_rand(11111, 99999));
	$timestamp = time();
	$init = 0;

	$result = mysql_query("INSERT INTO MobileAccess VALUES(NULL, '".safeInsert($app)."', '".safeInsert($mail)."', '".safeInsert($secret)."', '$random', 0, '$timestamp', '0', '0', '0', 0)", $XCOW_B['mysql_link']);
    if ($result) {

		log2file("MOBILE REGISTER SUCCES: app=".$app.", mail=".$mail.", secret=***");

		$init = 1;
	}
	else {
		catchMysqlError("mobileRegisterInit", $XCOW_B['mysql_link']);
	}
	
	return $init;
}

function mobileRegisterActivate($app, $mail, $key) {
    global $XCOW_B;

	$status = NULL;
	$active = 0;
	$id = 0;
	$token = 0;
	$secret = 0;

	//
	// Check app/mail/secret combo
	//
	$result = mysql_query("SELECT MobileAccessId, MobileAccessSecret FROM MobileAccess Where MobileAccessApp = '".safeInsert($app)."' AND MobileAccessEmail = '".safeInsert($mail)."'", $XCOW_B['mysql_link']);
	if ($result) {
	    if (mysql_num_rows($result) != 0) {
	
			log2file("MOBILE REGISTER ACTIVATE: app=".$app.", mail=".$mail.", secret=***");

			while ($result_row = mysql_fetch_assoc($result)) {
				$id = $result_row['MobileAccessId'];
				$secret = $result_row['MobileAccessSecret'];

				$token = md5($app.$mail.microtime().date("r").mt_rand(11111, 99999));
				
				if ($key == sha1($app.$mail.$secret)) {
					$AuthResult = mysql_query("UPDATE MobileAccess SET MobileAccessActive = 1, MobileAccessToken = '".$token."' WHERE MobileAccessId = $id", $XCOW_B['mysql_link']);
					$active = 1;
					break;
				}
				else {
					$status = "session_status_mobile_register_notactivated";
				}
			}
		}
		else {
			$status = "session_status_mobile_register_notactivated";
		}

	}
	else {
		catchMysqlError("mobileRegisterActivate", $XCOW_B['mysql_link']);
		$status = "session_status_mobile_register_notactivated";
	}

	return array($active, $status, $id, $token, $secret);
}

function mobileRegisterDelete($id) {

	global $XCOW_B;

	$result = mysql_query("DELETE FROM MobileAccess WHERE MobileAccessId = $id", $XCOW_B['mysql_link']);
    if ($result) {
		log2file("MOBILE REGISTER DELETE: id=".$id);
		return 1;
	}
	else {
		catchMysqlError("mobileRegisterDelete", $XCOW_B['mysql_link']);
		return 0;
	}

}

function mobileSessionConnect($id, $sessionId) {

	global $XCOW_B;

    $result = mysql_query("UPDATE MobileAccess SET SessionId = $sessionId WHERE MobileAccessId = $id", $XCOW_B['mysql_link']);
    if (! $result) {
		catchMysqlError("mobileSessionConnect", $XCOW_B['mysql_link']);
		return 0;
	}
	
	return 1;
}

function mobileSessionIsActive($id) {
        global $XCOW_B;

        $result = mysql_query("SELECT MobileAccessActive FROM MobileAccess WHERE MobileAccessId = $id", $XCOW_B['mysql_link']);
		if ($result) {
			$result_row = mysql_fetch_row($result);
			return ($result_row[0]);
		}
		else {
			catchMysqlError("mobileSessionIsActive", $XCOW_B['mysql_link']);
			return 0;
		}
}

?>
