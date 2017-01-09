<?

class mailUser extends control {

    function Run() {

        global $XCOW_B;
        
		// who?
		$this->id = $this->ses['request']['param']['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		// mode=send: sendMail
		// default: display
		$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		
		// init
		// - mailInterval: frequency of mail in days (one every seven days)
		$mailInterval = 7;
		
		$mailInfo = array();
		$mailPreferenceName = "mailUserPreference";
		$mailPreferenceValue = "";
		$mailPreferenceId = 0;
		$mailLastName = "mailUserLast";
		$mailLastValue = "";
		$mailLastId = 0;
		$mailLanguageName = "mailLanguage";
		$mailLanguageValue = "";
		
		// check userData for action (but only if userId exists!)
		// 0. do nothing
		// 1. create user data entry if it does not exist.
		// 2. if mailPreference is 1 (the user indicated to receive mail)
		//    AND mailLast < 1 week (the user did not receive mail last week)
		//    then mailLast is now & sendmail;
		$status = "Nothing to be done";
		$action = 0;
		$timeStamp = time() - (24 * 60 * 60 * $mailInterval);
		if ($this->userId != 0) {
			
			// make sure the user is NOT active, otherwise this mail is not necessary
			if (isActiveFromUserId($this->id) == 0) {

				$userData = UserApiListDataWithQuery("userId=".$this->userId);
				foreach ($userData as $dataId => $dataList) {
					if ($dataList['Name'] == $mailPreferenceName) {
						$mailPreferenceValue = $dataList['Value'];
						$mailPreferenceId = $dataList['Id'];
					}
					if ($dataList['Name'] == $mailLastName) {
						$mailLastValue = $dataList['Value'];
						$mailLastId = $dataList['Id'];
					}
					if ($dataList['Name'] == $mailLanguageName) {
						$mailLanguageValue = $dataList['Value'];
					}
				}
				
				if ($mailPreferenceValue == "" || $mailLastValue == "") {
					$action = 1;
				}
				elseif ($mailPreferenceValue == 1 && $mailLastValue < $timeStamp) {
					$action = 2;
				}
				if ($mailLanguageValue == "") {
					$mailLanguageValue = $XCOW_B['default_language'];
				}
		
				// override normal behaviour for debug purposes
				if ($this->mode == "onlyDisplay") {
					$action=2;
				}
						
			}
		}
		
		// perform action
		// - create defaults for this user
		if ($action == 1) {
			if ($mailPreferenceValue == "") {
				$dataEntry = array();
				$dataEntry['userId'] = $this->userId;
				$dataEntry['name'] = $mailPreferenceName;
				$dataEntry['value'] = 1;
				UserApiSaveData($dataEntry);
			}
			if ($mailLastValue == "") {
				$dataEntry = array();
				$dataEntry['userId'] = $this->userId;
				$dataEntry['name'] = $mailLastName;
				$dataEntry['value'] = time();
				UserApiSaveData($dataEntry);
			}
			
			log2file("Created 'User Mail' data entry for id: ".$this->id);
			$status = "Created data entry for user";

		}
		// - sendmail for user
		elseif ($action == 2) {
			// new timestamp is set to 10 * mailInterval (=70 days)
			// note: within these  days the account must be deleted... otherwise a new reminder is send
			if ($this->mode != "onlyDisplay") {
				$dataEntry = array();
				$dataEntry['name'] = $mailLastName;
				$dataEntry['value'] = time() + (24 * 60 * 60 * $mailInterval * 10);
				UserApiUpdateData($mailLastId, $dataEntry);
			}

			$mailInfo['language'] = $mailLanguageValue;
			
			######
			# USER
			######
			$userInfo = current(UserApiListUserById($this->userId));
			$mailInfo['email'] = getUserEmailFromUserId($userInfo['Reference']);
			$mailInfo['key'] = getUserKeyFromUserId($userInfo['Reference']);
			$mailInfo['name'] = $mailInfo['email'];
		
			######
			# MAIL
			######
			#print_r($mailInfo);

			// addresses
			$receiver_name = $mailInfo['name'];
			$receiver_mail = $mailInfo['email'];

			$sender_name = $XCOW_B['this_name'];
			$sender_mail = $XCOW_B['this_mail'];

			// subject
			$subject = "";
			switch ($mailLanguageValue) {
				case "en":
					$subject = "Remember to activate your Sciomino account";
					break;
				default:
					$subject = "Vergeet niet je Sciomino account te activeren";
			}
			
			// body
			$url = $XCOW_B['this_host']."/session/activate?key=".$mailInfo['key'];

			$bodyFiller['user'] = $mailInfo['name'];
			$bodyFiller['name'] = $XCOW_B['this_name'];
			$bodyFiller['url'] = $url;
			$bodyFiller['host'] = $XCOW_B['this_host'];
			
			$body = mail_template('3.2.user-update', $bodyFiller, $mailLanguageValue);

			// names AND emails are checked in sendMail
			if ($this->mode == "onlyDisplay") {
				$status = $body;
			}
			if ($this->mode == "send") {
				$sendStatus = sendMailWithHTML($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);
				log2file("Send User Mail to: ".$receiver_mail);

				if ($sendStatus == 0) { $status = language('sciomio_mail_user_status_wrong'); }
				if ($sendStatus == 1) { $status = language('sciomio_mail_user_status_ok'); }

			}
		}

		# output
		$this->ses['response']['param']['status'] = $status;

     }
     
}

?>
