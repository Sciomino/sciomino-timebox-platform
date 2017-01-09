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

	    }
	    else {
                    $status = "session_status_activate_wrongkey";
            }

        }
	else {
                $status = "session_status_activate_nokey";
		#default view
        }

        $this->ses['response']['param']['status'] = $status;

	$this->ses['response']['redirect'] = $XCOW_B['this_host'];

    }

}

?>
