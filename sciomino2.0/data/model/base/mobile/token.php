<?php

class mobileToken extends control {

    function Run() {

		global $XCOW_B;
		$message = NULL;
		$status = 0;
		$isNew = 0;

		# from app form
		$this->email = $this->ses['request']['param']['email'];
		$this->key = $this->ses['request']['param']['key'];
		if (! isset($this->key)) {$this->key = "";}
		$this->name = $this->ses['request']['param']['name'];
		if (! isset($this->name)) {$this->name = "";}
		
		// try
	    list($status, $message, $mobileId, $token, $secret) = mobileRegisterActivate($this->name, $this->email, $this->key);
	    
	    //
	    // Continu activation
	    //
        if ($status) {
			# find existing user
			$sessionId = getUserIdFromEmail($this->email);
			
			if ($sessionId) {
				mobileSessionConnect($mobileId, $sessionId);
			}
			else {
				# create session
				registerActivate($this->email, md5($secret), $this->email);
				activateSession($this->email);
				$sessionId = getUserIdFromUserName($this->email);

				# create user in api
				$user = array();
				$contact = array();
				$user['loginName'] = $this->email;
				$contact['Work'] = array();
				$contact['Work']['email'] = $this->email;
				if (($this->userId = UserApiCreateUser($user, $sessionId)) != 0) {
					$this->contactId = ScioMinoApiUpdateContact($contact, $this->userId);
				}
				else {
				// something terribly wrong, now what?
				}
				
				# connect mobile session to session
				mobileSessionConnect($mobileId, $sessionId);

				$isNew = 1;
			}
		}

		// return status to app
        $this->ses['response']['param']['status'] = $status;
        if (isset($message)) {
			$this->ses['response']['param']['message'] = language($message);
		}
		else {
			$this->ses['response']['param']['message'] = "";
		}
		$this->ses['response']['param']['token'] = $token;
		$this->ses['response']['param']['new'] = $isNew;

                // allow resource, for testing only!!!
                $this->ses['response']['header'] = "Access-Control-Allow-Origin:*";

    }

}

?>

