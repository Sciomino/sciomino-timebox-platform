<?

class sessionActivate extends control {

    function Run() {

        global $XCOW_B;

	$status = NULL;

	$this->key = $this->ses['request']['param']['key'];

        if (isset ($this->key)) {

	    $user = getUserNameFromKey($this->key);
	    if ($user) {
		    activateSession($user);
		    startSession($this->ses, $user);
		    $status = "session_status_activate_ok";

			$this->ses['response']['redirect'] = $XCOW_B['this_host']."/wizard";

	    }
	    else {
                    $status = "session_status_activate_wrongkey";
                    $this->ses['response']['redirect'] = "/error404";
            }

        }
		else {
                $status = "session_status_activate_nokey";
                $this->ses['response']['redirect'] = "/error404";
		#default view
        }

        $this->ses['response']['param']['status'] = $status;


    }

}

?>
