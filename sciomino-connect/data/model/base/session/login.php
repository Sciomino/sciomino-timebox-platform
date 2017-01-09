<?

class sessionLogin extends control {

    function Run() {

        global $XCOW_B;
        
        $status = NULL;

	$this->user = $this->ses['request']['param']['user'];
	$this->pass = $this->ses['request']['param']['pass'];

	# callback = [0|1] & redirect = [URL]
	$this->callback = $this->ses['request']['param']['callback'];
	$this->redirect = $this->ses['request']['param']['redirect'];

        #
        # user supplied a username and password
        #
        if (isset($this->user)) {

	    if (checkLogin($this->user, $this->pass)) {
		startSession($this->ses, $this->user);
            
	        $status = "session_status_login_ok";
 
		$this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/login2.php';
		if ($this->redirect != '') {
			$this->ses['response']['redirect'] = $this->redirect;
		}
            }
            #
            # wrong username and/or password
            #
            else {
	    	$this->ses['response']['param']['prevName'] = $this->user;
	        $status = "session_status_login_wrong";
		if ($this->redirect != '') {
			$this->ses['response']['param']['redirect'] = $this->redirect;
		}
            }

        }
        #
        # first time visitor, show authentication form!
        #
        else {
		#$status = "Please supply your username and password for access to this page";
		
		if ($this->callback) {
	    
	            $status = "session_status_login_firsttime";
 
  	            if ($this->redirect != '') {
  	                $this->ses['response']['param']['redirect'] = $this->redirect;
  	            }
    	            else {
   	                $this->ses['response']['param']['redirect'] = $this->ses['request']['script_name']."/".$this->ses['request']['path_info']."?".$this->ses['request']['query_string'];
    	            }        

		}
	}

        $this->ses['response']['param']['status'] = $status;

    }

}

?>
