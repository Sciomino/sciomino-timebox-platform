<?

class passNew extends control {

    function Run() {

        global $XCOW_B;

        $status = NULL;
	$prevMail = "";

        $this->mail = $this->ses['request']['param']['mail'];

        //
        // an e-mail is needed for input
        // otherwise proceed to the view and show a form where a new e-mail can be entered
        //
        if (isset($this->mail)) {

	    $user = getUserNameFromEmail($this->mail);

            if (isset($user)) {
		$passNew = generatePass();
		updatePassword($user, $passNew);

		#
		# password mail
		#
		$subject = language("session_mail_passnew_subject");      
		$bodyFiller['user'] = $user;
		$bodyFiller['name'] = $XCOW_B['this_name'];
		$bodyFiller['pass'] = $passNew;
		$bodyFiller['host'] = $XCOW_B['this_host'];
		$body = language_template('session_mail_passnew_body', $bodyFiller);
 		sendMail("${XCOW_B['this_name']}", "${XCOW_B['this_mail']}", $user, $this->mail, $subject, $body);

		$status = "session_mail_passnew_status";
            	$this->ses['response']['view'] = $XCOW_B['view_base'].'/base/session/passNew2.php';
            }
	    else {
		$status = "session_status_passnew_emailwrong";
		$this->ses['response']['param']['prevMail'] = $this->mail;
	    }

            $this->ses['response']['param']['status'] = $status;
        }

    }

}

?>
