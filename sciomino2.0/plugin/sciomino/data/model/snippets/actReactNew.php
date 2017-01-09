<?

class actReactNew extends control {

    function Run() {

        global $XCOW_B;

		$actId = 0;
		$this->act = array();
		$actList = array();

		// who?
        $this->id = $this->ses['id'];

		// params

		$this->act['parent'] = $this->ses['request']['param']['com_act'];
		$this->act['description'] = $this->ses['request']['param']['com_description'];

		//
		// check fields?
		//
		$input = array($this->act['description'], $this->act['parent']);
		if (! noEmptyInput($input) ) {
			$this->status = "Input Error";
		}
	
        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {
			// save, BEWARE save with id, NOT with userId
			$actId = AnswersApiSaveAct ($this->act, $this->id, '1'); 

			if ($actId != 0) {
				$this->status = "De act is toegevoegd.";

				// get act info (for display)
				$actString = "act[".$actId."]&parent=".$this->act['parent'];
				$actList = AnswersApiListActWithQuery($actString);
				$this->ses['response']['param']['act'] = $actList[$actId];

				$actRef = $actList[$actId]['Reference'];
				$userString = "user[".UserApiGetUserFromReference($actRef)."]&format=short";
				$user = current(UserApiListUserWithQuery($userString));
				$this->ses['response']['param']['user'] = $user;
				$this->ses['response']['param']['userRef'] = $this->id;

				// get parent info (for mail)
				$parentAct = current(AnswersApiListActById($this->act['parent']));
				$parentUserString = "user[".UserApiGetUserFromReference($parentAct['Reference'])."]&format=short";
				$parentUser = current(UserApiListUserWithQuery($parentUserString));

				// mail to parent user (but not to self)
				if ($parentUser['Reference'] != $user['Reference']) {
					$receiver_name = $parentUser['FirstName'];
					$receiver_mail = getUserEmailFromUserId($parentUser['Reference']);

					$sender_name = $user['FirstName']." ".$user['LastName'];
					$sender_mail = getUserEmailFromUserId($user['Reference']);
					
					// get photo stuff
					if (! isset($user['photo'])) { $user['photo'] = "/ui/gfx/photo.jpg"; }
					else { $user['photo'] = str_replace("/upload/","/upload/48x48_",$user['photo']); }
					$photo = $XCOW_B['this_host'].$user['photo'];
					$photo_url = $XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$user['Id'];
					
					$subjectFiller['name'] = $XCOW_B['this_name'];
					$subjectFiller['user'] = $sender_name;
					$subject = language_template('sciomio_mail_act_react_subject', $subjectFiller);

					$bodyFiller['name'] = $XCOW_B['this_name'];
					$bodyFiller['user'] = $sender_name;
					$bodyFiller['avatar'] = $photo;
					$bodyFiller['avatar_url'] = $photo_url;
					$bodyFiller['message'] = $this->act['description'];
					$bodyFiller['actMessage'] = $parentAct['Description'];
					$bodyFiller['actUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/act/view?act=".$parentAct['Id'];
					// $body = language_template('sciomio_mail_act_react_body', $bodyFiller);
					$body = mail_template('2.4.react', $bodyFiller, $this->ses['response']['language']);

					// names AND emails are checked in sendMail
					$sendStatus = sendMailWithHTML($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);

					if ($sendStatus == 0) { $status = language('sciomio_mail_act_react_status_wrong'); }
					if ($sendStatus == 1) { $status = language('sciomio_mail_act_react_status_ok'); }
				}

			}
			else {
				$this->status = "De act kon niet toegevoegd worden.";
			}

			$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/actReactNew.php';
        }
        
		// show the form
        else {
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
