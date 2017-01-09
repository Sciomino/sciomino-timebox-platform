<?

class sessionNew extends control {

    function Run() {

        global $XCOW_B;

        $status = NULL;
	$showMe = 1;

	$this->go = $this->ses['request']['param']['go'];
	$this->user = $this->ses['request']['param']['user'];
	$this->pass = $this->ses['request']['param']['pass'];
	$this->mail = $this->ses['request']['param']['mail'];

	if ($XCOW_B['session_user_is_mail']) {
		$this->mail = $this->user;
	}

	// local additions
	$this->userInfo = array();
	$this->contact = array();
	$this->contact['Work'] = array();
	$this->userInfo['loginName'] = $this->user;
	$this->contact['Work']['email'] = $this->mail;

	//
	// check fields?
	//
	if (getSessionCount() > $XCOW_B['session_user_max'] && $XCOW_B['session_user_max'] != 0) {
		$showMe = 2;
	}
	if ($XCOW_B['sciomino']['skin-register'] != "yes") {
		$showMe = 3;
	}
	$input = array($this->user, $this->pass, $this->mail);
	if (! noEmptyInput($input) ) {
		$status = language("session_status_register_requiredfield");
	}
	
        //
        // if the fields are checked, register the user
        // otherwise proceed to the view and show a form where a new user can be entered
        //
        if ( ($this->go == 1) && (! isset($status)) && ($showMe == 1)) {

	    $status = registerAttempt($this->user, $this->pass, $this->mail);
	    
	    //
	    // Continu registration
	    //
            if (! isset($status)) {
		    #
		    # encrypt password
		    #
		    $this->pass = md5($this->pass);

		    $activateKey = registerActivate($this->user, $this->pass, $this->mail);

		    if ($XCOW_B['session_activate_mail']) {
			    #
			    # activate mail
			    #
			    $url = $XCOW_B['this_host']."/session/activate?key=".$activateKey;

			    $subject = language('session_mail_register_subject');
			    $bodyFiller['user'] = $this->user;
			    $bodyFiller['name'] = $XCOW_B['this_name'];
			    $bodyFiller['url'] = $url;
			    $bodyFiller['host'] = $XCOW_B['this_host'];
			    // $body = language_template('session_mail_register_body', $bodyFiller);
				$body = mail_template('1.1.activate', $bodyFiller, $this->ses['response']['language']);
			    sendMailWithHTML("${XCOW_B['this_name']}", "${XCOW_B['this_mail']}", $this->user, $this->mail, $subject, $body);

			    $status = "session_mail_register_status";
		    }
		    else {
			    activateSession($this->user);
			    startSession($this->ses, $this->user);

			    #
			    # register mail
			    #
			    $subject = language('session_mail_register2_subject');
			    $bodyFiller['user'] = $this->user;
			    $bodyFiller['name'] = $XCOW_B['this_name'];
			    $bodyFiller['host'] = $XCOW_B['this_host'];
			    // $body = language_template('session_mail_register2_body', $bodyFiller);
				$body = mail_template('1.2.register', $bodyFiller, $this->ses['response']['language']);
			    sendMailWithHTML("${XCOW_B['this_name']}", "${XCOW_B['this_mail']}", $this->user, $this->mail, $subject, $body);

			    $status = "session_mail_register2_status";
		    }

		    // init new user
		    $reference = getUserIdFromUserName($this->user);
		    if (($this->userId = UserApiCreateUser($this->userInfo, $reference)) != 0) {
				$this->contactId = ScioMinoApiUpdateContact($this->contact, $this->userId);
		    }
		    else {
			// something terribly wrong, now what?
		    }

		    $this->ses['response']['param']['status'] = $status;
	    	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/new2.php';

            }
	    // registration attempt failed, try again
	    else {
	    	    $this->ses['response']['param']['prevName'] = $this->user;
	    	    $this->ses['response']['param']['prevMail'] = $this->mail;


		    $this->ses['response']['param']['status'] = $status;
            }
        }
	// not all required fields
	else {
		if ( $this->go == 1 ) {
		    	$this->ses['response']['param']['prevName'] = $this->user;
		    	$this->ses['response']['param']['prevMail'] = $this->mail;

	        	$this->ses['response']['param']['status'] = $status;
		}

		if ( $showMe == 2 ) {
			$status = language("session_status_register_toomany");
	        	$this->ses['response']['param']['status'] = $status;
	    		$this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/new2.php';
		}
		elseif ( $showMe == 3 ) {
			$status = language("session_status_register_notshown");
	        	$this->ses['response']['param']['status'] = $status;
	    		$this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/new2.php';
		}
	}

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

    }

}

?>
