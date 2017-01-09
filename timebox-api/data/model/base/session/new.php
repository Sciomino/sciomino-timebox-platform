<?

class sessionNew extends control {

    function Run() {

        global $XCOW_B;

        $status = NULL;
	$prevName = "";
	$prevMail = "";

	$this->user = $this->ses['request']['param']['user'];
	$this->pass = $this->ses['request']['param']['pass'];
	$this->mail = $this->ses['request']['param']['mail'];

        //
        // if the user is set, register the user
        // otherwise proceed to the view and show a form where a new user can be entered
        //
        if (isset ($this->user)) {

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
			    $body = language_template('session_mail_register_body', $bodyFiller);
			    sendMail("${XCOW_B['this_name']}", "${XCOW_B['this_mail']}", $this->user, $this->mail, $subject, $body);

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
			    $body = language_template('session_mail_register2_body', $bodyFiller);
			    sendMail("${XCOW_B['this_name']}", "${XCOW_B['this_mail']}", $this->user, $this->mail, $subject, $body);

			    $status = "session_mail_register2_status";
		    }

	    	    $this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/new2.php';

            }
	    // registration attempt failed, try again
	    else {
	    	    $this->ses['response']['param']['prevName'] = $this->user;
	    	    $this->ses['response']['param']['prevMail'] = $this->mail;
            }

            $this->ses['response']['param']['status'] = $status;
        }

    }

}

?>
