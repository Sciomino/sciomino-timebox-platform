<?php

class mobileAuthorize extends control {

    function Run() {

		global $XCOW_B;
		$message = NULL;
		$status = 0;

		# from app form
		$this->email = $this->ses['request']['param']['email'];
		$this->user = $this->ses['request']['param']['user'];
		if (! isset($this->user)) {$this->user = $this->email;}
		$this->name = $this->ses['request']['param']['name'];
		if (! isset($this->name)) {$this->name = "";}
		
		// try
	    $message = mobileRegisterAttempt($this->name, $this->email);
	    
	    //
	    // Continu registration
	    //
        if (! isset($message)) {
			$secret = generatePin();
		    
		    if (mobileRegisterInit($this->name, $this->email, $secret)) {
				# mail secret
				$subjectFiller['name'] = $this->name;
				$subjectFiller['secret'] = $secret;
				$subject = language_template('session_mail_mobile_subject', $subjectFiller);

				$bodyFiller['name'] = $this->name;
				$bodyFiller['user'] = $this->user;
				$bodyFiller['email'] = $this->email;
				$bodyFiller['secret'] = $secret;
				$body = language_template('session_mail_mobile_body', $bodyFiller);
				
				sendMail("${XCOW_B['this_name']}", "${XCOW_B['this_mail']}", $this->user, $this->email, $subject, $body);

				$status = 1;
			}
		}
	    // registration attempt failed
	    else {
			$status = 0;
        }

		// return status to app
        $this->ses['response']['param']['status'] = $status;
        if (isset($message)) {
			$this->ses['response']['param']['message'] = language($message);
		}
		else {
			$this->ses['response']['param']['message'] = "";
		}
		
                // allow resource, for testing only!!!
                $this->ses['response']['header'] = "Access-Control-Allow-Origin:*";

    }

}

?>

