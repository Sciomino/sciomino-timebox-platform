<?

class actMailNew extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	$userName = "";
	$addressString = "";
	$count = 0;

	// params
	$this->act = $this->ses['request']['param']['act'];

	$this->message = $this->ses['request']['param']['com_message'];
	$this->address = $this->ses['request']['param']['address'];
	
	if (isset($this->address)) {
		$count = count($this->address);
	}

	//
	// check fields?
	//
	$input = array($this->message);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	// act info
	$actList = AnswersApiListActById($this->act);
	$actDescription = $actList[$this->act]['Description'];

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new mail can be entered
        //
        if (! $this->status && $count > 0) {
		// all user info
		$query = "user[".$this->userId."]";
		foreach ($this->address as $address) {
			$query.= "&user[".$address."]";
		}
		$userList = UserApiListUserWithQuery($query);

		// this->user
		$userName = $userList[$this->userId]['FirstName']." ".$userList[$this->userId]['LastName'];
		$userMail = getUserEmailFromUserId($userList[$this->userId]['Reference']);
		
		// get photo stuff
		if (! isset($userList[$this->userId]['photo'])) { $userList[$this->userId]['photo'] = "/ui/gfx/photo.jpg"; }
		else { $userList[$this->userId]['photo'] = str_replace("/upload/","/upload/48x48_",$userList[$this->userId]['photo']); }
		$photo = $XCOW_B['this_host'].$userList[$this->userId]['photo'];
		$photo_url = $XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$userList[$this->userId]['Id'];

		// send to addresses
		foreach ($this->address as $address) {
			$receiver_name = $userList[$address]['FirstName'];
			$receiver_mail = getUserEmailFromUserId($userList[$address]['Reference']);

			$sender_name = $userName;
			$sender_mail = $userMail;

			$subjectFiller['name'] = $XCOW_B['this_name'];
			$subjectFiller['user'] = $userName;
			$subject = language_template('sciomio_mail_act_subject', $subjectFiller);

			$bodyFiller['name'] = $XCOW_B['this_name'];
			$bodyFiller['user'] = $userName;
			$bodyFiller['avatar'] = $photo;
			$bodyFiller['avatar_url'] = $photo_url;
			$bodyFiller['message'] = $this->message;
			$bodyFiller['actMessage'] = $actDescription;
			$bodyFiller['actUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/act/view?act=".$this->act;
			// $body = language_template('sciomio_mail_act_body', $bodyFiller);
			$body = mail_template('2.3.act', $bodyFiller, $this->ses['response']['language']);

			// names AND emails are checked in sendMail
			$sendStatus = sendMailWithHTML($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);

			if ($sendStatus == 0) { $status = language('sciomio_mail_act_status_wrong'); }
			if ($sendStatus == 1) { $status = language('sciomio_mail_act_status_ok'); }
			
		}

		$this->status = $status;
		//$this->status = "De mail is verstuurd.";
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/actMailNewForm2.php';

	}
        
	// show the form
        else {
        }

	$this->ses['response']['param']['act'] = $this->act;
	$this->ses['response']['param']['actDescription'] = $actDescription;
        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
