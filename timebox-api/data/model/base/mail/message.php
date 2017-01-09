<?

class mailMessage extends control {

    function Run() {

        global $XCOW_B;

        $status = NULL;

        //
        // if the flag is set, go for it
        // otherwise proceed to the view and show a form where a new message can be entered
        //
        if (isset ($this->ses['request']['param']['flag'])) {

	    $sender_name = $this->ses['request']['param']['sender_name'];
	    $sender_mail = $this->ses['request']['param']['sender_mail'];

	    // Could use form to specify receiver
	    // $receiver_name = $this->ses['request']['param']['receiver_name'];
	    // $receiver_mail = $this->ses['request']['param']['receiver_mail'];
	    $receiver_name = $XCOW_B['this_name'];
	    $receiver_mail = $XCOW_B['this_mail'];

	    $subject = $this->ses['request']['param']['subject'];
	    $body = secureInput($this->ses['request']['param']['body'],0);

	    // names AND emails are checked in sendMail
	    $sendStatus = sendMail($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);

	    if ($sendStatus == 0) { $status = "base_status_mail_send_wrong"; }
	    if ($sendStatus == 1) { $status = "base_status_mail_send_ok"; }

            $this->ses['response']['param']['status'] = $status;
            $this->ses['response']['view'] = $XCOW_B['view_base'].'/base/mail/message2.php';
        }
	else {
	
        }

    }

}

?>
