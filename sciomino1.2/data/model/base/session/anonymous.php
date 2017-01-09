<?

class sessionAnonymous extends control {

    function Run() {

        global $XCOW_B;
        
        $id = 0;

        #
        # anonymous login
        #
        if ($XCOW_B['anonymous']) {

            session_save_path($XCOW_B['session_save_path']);
            session_name($XCOW_B['session_name']);
            session_start();

            # session started
            if (isset($_SESSION['ControlSessionKey'])) {
                $id = getAnonymousIdFromKey($_SESSION['ControlSessionKey']);
            }
            # new session
            else {
                $key = session_id();
                $_SESSION['ControlSessionKey']  = $key;
		$id = startAnonymousSession($this->ses, $key);
            }

        }

        $this->ses['response']['param']['id'] = $id;

    }

}

?>
