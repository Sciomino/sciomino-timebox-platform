<?

class sessionLogout extends control {

    function Run() {

        global $XCOW_B;

	$id = $this->ses['id'];

        #
        # user was logged in
        #
        if (isset($id)) {

	    stopSession($id);
            
	    $this->ses['response']['param']['status'] = "session_status_logout_ok";

        }
	#
        # user was not logged in
	#
        else {

            $this->ses['response']['param']['status'] = "session_status_logout_wrong";
 
        }

    }

}

?>
