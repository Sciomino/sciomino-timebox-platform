<?

class helpNew extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	$userName = "";
	$addressString = "";
	$count = 0;

	// params
	$this->activity = makeIntString($this->ses['request']['param']['activity']);

	$this->user = makeIntString($this->ses['request']['param']['user']);

	$this->knowledge = $this->ses['request']['param']['knowledge'];

	$this->message = $this->ses['request']['param']['com_message'];

	$this->addNew = $this->ses['request']['param']['add-new'];
	$this->level = $this->ses['request']['param']['level'];

	$this->go = $this->ses['request']['param']['go'];
	if (! isset($this->go)) { $this->go = 0; }


	//
	// check fields?
	//
	$missingFields = 0;
	$input = array($this->activity, $this->user, $this->message);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
		$missingFields = 1;
	}

	// all user info
	$query = "user[".$this->userId."]&user[".$this->user."]";
	$userList = UserApiListUserWithQuery($query);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new mail can be entered
        //
        if (! $this->status) {

		// this->user
		$userName = $userList[$this->userId]['FirstName']." ".$userList[$this->userId]['LastName'];
		$userMail = getUserEmailFromUserId($userList[$this->userId]['Reference']);

		// get photo stuff
		if (! isset($userList[$this->userId]['photo'])) { $userList[$this->userId]['photo'] = "/ui/gfx/photo.jpg"; }
		else { $userList[$this->userId]['photo'] = str_replace("/upload/","/upload/48x48_",$userList[$this->userId]['photo']); }
		$photo = $XCOW_B['this_host'].$userList[$this->userId]['photo'];
		$photo_url = $XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$userList[$this->userId]['Id'];

		$receiver_name = $userList[$this->user]['FirstName'];
		$receiver_mail = getUserEmailFromUserId($userList[$this->user]['Reference']);

		$sender_name = $userName;
		$sender_mail = $userMail;

		$subjectFiller['name'] = $XCOW_B['this_name'];
		$subjectFiller['user'] = $userName;
		$subjectFiller['knowledge'] = $this->knowledge;
		$subject = language_template('sciomio_mail_help_subject', $subjectFiller);

		$bodyFiller['name'] = $XCOW_B['this_name'];
		$bodyFiller['user'] = $userName;
		$bodyFiller['avatar'] = $photo;
		$bodyFiller['avatar_url'] = $photo_url;
		$bodyFiller['knowledge'] = $this->knowledge;
		$bodyFiller['message'] = $this->message;
		// $body = language_template('sciomio_mail_help_body', $bodyFiller);
		$body = mail_template('2.2.help', $bodyFiller, $this->ses['response']['language']);

		// names AND emails are checked in sendMail
		$sendStatus = sendMailWithHTML($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);

		if ($sendStatus == 0) { $status = language('sciomio_mail_help_status_wrong'); }
		if ($sendStatus == 1) { $status = language('sciomio_mail_help_status_ok'); }

		$this->status = $status;
		//$this->status = "De mail is verstuurd.";

		if ($this->addNew) {

			$this->knowledgeItem = array();
			$this->knowledgeItem['field'] = $this->knowledge;
			$this->knowledgeItem['level'] = $this->level;

		        $this->knowledgeItem['field'] = ucfirst(strtolower($this->knowledgeItem['field']));
        		$knowledgeList = ScioMinoApiListKnowledge($this->userId);
			$statusAgain = NULL;
        		foreach ($knowledgeList as $key => $val) {
                		if (ucfirst(strtolower($val['field'])) == $this->knowledgeItem['field']) {
                        		$statusAgain = "Same Same";
                        		break;
                		}
        		}
			if (! $statusAgain) {
				$knowledgeId = ScioMinoApiSaveKnowledge($this->knowledgeItem, $this->userId, '1');
			}

			// remove from list
			UserApiDeleteActivity($this->activity);
		}

		// view
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/helpNewForm2.php';

	}
        
	// show the form
        else {
		#
			$this->ses['response']['param']['prevMessage'] = $this->message;
			$this->ses['response']['param']['go'] = $this->go;
 			$this->ses['response']['param']['missing'] = $missingFields;
      }

	$this->ses['response']['param']['activity'] = $this->activity;
	$this->ses['response']['param']['user'] = $this->user;
	$this->ses['response']['param']['knowledge'] = $this->knowledge;
	$this->ses['response']['param']['userName'] = $userList[$this->user]['FirstName'];;
        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
