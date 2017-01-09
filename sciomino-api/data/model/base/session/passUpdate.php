<?

class passUpdate extends control {

    function Run() {

        global $XCOW_B;
        
	$status = NULL;

	$this->id = $this->ses['id'];

	$this->passOld = $this->ses['request']['param']['passOld'];
	$this->passNew1 = $this->ses['request']['param']['passNew1'];
	$this->passNew2 = $this->ses['request']['param']['passNew2'];

        #
        # needs triple passwords
	# - if no password, then show the form
        #
        if (isset($this->passOld)) {

	    $user = getUserNameFromUserId($this->id);
	    
	    if (checkLogin ($user, $this->passOld)) {

		if ($this->passNew1 != $this->passNew2) {
			$status = "session_status_passupdate_nomatch";
 		}
		elseif (! isValidPass($this->passNew1)) {
			$status = "session_status_passupdate_newwrong";
		}
		else {
			updatePassword($user, $this->passNew1);
			$status = "session_status_passupdate_ok";
 			$this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/passUpdate2.php';
		}

            }
            #
            # wrong password
            #
            else {

		$status = "session_status_passupdate_oldwrong";
 
            }

            $this->ses['response']['param']['status'] = $status;

        }

    }

}

?>
