<?

class mailReact extends control {

    function Run() {

        global $XCOW_B;
  
  		// flow:
		// - traverse latest reacts (in cron job)
		// - foreach new react -> mailReact
		// --- param: act_id (and not id)
		// - for all people in react list
		// - send mail, unless
		// --- react is from person self
		// --- person has blocked the act
		// --- person is owner of act (already send)
      
		// what?
		$this->id = $this->ses['request']['param']['id'];

		// mode=send: sendMail
		// default: display
		$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		
		// init
		$actList = array();
		$act = array();
		$reactList = array();
		
		$mailInfo = array();
		$mailLanguageName = "mailLanguage";
		$mailLanguageValue = "";

		// act info
		$actList = AnswersApiListActById($this->id);
		$act = $actList[$this->id];
		$mailInfo['actDescription'] = $act['Description'];
		$mailInfo['actOwner'] = $act['Reference'];
		
		$userString = "refX[".$act['Reference']."]";

		// react info
		$reactString = "parent=".$this->id."&order=time&direction=desc";
		$reactList = AnswersApiListActWithQuery($reactString);

		// traverse reactlist, the first one is the latest
		$first = 1;
		$refSeen = array();
		foreach ($reactList as $reactKey => $reactVal) {
			if ($first) {
				$mailInfo['reactDescription'] = $reactVal['Description'];
				$mailInfo['reactOwner'] = $reactVal['Reference'];
				$first = 0;
			}
			if (! in_array($reactVal['Reference'], $refSeen)) {
				$refSeen[] = $reactVal['Reference'];
				$userString .= "&refX[".$reactVal['Reference']."]";
			}
		}
		
		// user info (of act & reacts)
		$userString .= "&format=short";
		$userList = UserApiListUserWithQuery($userString);
		$mailInfo['actUser'] = get_id_from_multi_array($userList, 'Reference', $mailInfo['actOwner']);
		$mailInfo['reactUser'] = get_id_from_multi_array($userList, 'Reference', $mailInfo['reactOwner']);

		// get photo stuff of react
		if (! isset($userList[$mailInfo['reactUser']]['photo'])) { $userList[$mailInfo['reactUser']]['photo'] = "/ui/gfx/photo.jpg"; }
		else { $userList[$mailInfo['reactUser']]['photo'] = str_replace("/upload/","/upload/48x48_",$userList[$mailInfo['reactUser']]['photo']); }
		$mailInfo['photo'] = $XCOW_B['this_host'].$userList[$mailInfo['reactUser']]['photo'];
		$mailInfo['photo_url'] = $XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$userList[$mailInfo['reactUser']]['Id'];

		// send mail to all refs
		foreach ($refSeen as $ref) {
			
			// but not to owner of the act or the person himself
			if ($ref != $mailInfo['actOwner'] && $ref != $mailInfo['reactOwner']) {
				
				// check Mailblock
				$actMailblockCount = count($act['Mailblock']);
				if ( $actMailblockCount > 0 ) {
					if (get_id_from_multi_array($act['Mailblock'], 'Reference', $ref) != 0) {
						# blocked, so skip this ref
						continue;
					}
				}
				
				// set mail language based on user preference
				$this->userId = UserApiGetUserFromReference($ref, "SC_UserApiGetUserFromReference_".$ref);
				if ($this->userId != 0) {
					$userData = UserApiListDataWithQuery("userId=".$this->userId);
					foreach ($userData as $dataId => $dataList) {
						if ($dataList['Name'] == $mailLanguageName) {
							$mailLanguageValue = $dataList['Value'];
						}
					}

					if ($mailLanguageValue == "") {
						$mailLanguageValue = $XCOW_B['default_language'];
					}
				}
				$mailInfo['language'] = $mailLanguageValue;

				######
				# USER
				######
				$mailInfo['email'] = getUserEmailFromUserId($ref);
				$mailInfo['name'] = $userList[$this->userId]['FirstName'];
			
				######
				# MAIL
				######
				//print_r($mailInfo);
				
				// addresses
				$receiver_name = $mailInfo['name'];
				$receiver_mail = $mailInfo['email'];

				$sender_name = $XCOW_B['this_name'];
				$sender_mail = $XCOW_B['this_mail'];

				// subject
				$subject = "";
				switch ($mailLanguageValue) {
					case "en":
						$subject = "Stay on top of the questions and answers";
						break;
					default:
						$subject = "Blijf op de hoogte van de vragen en antwoorden";
				}
				
				// body
				$bodyFiller['user'] = $mailInfo['name'];
				$bodyFiller['name'] = $XCOW_B['this_name'];
				$bodyFiller['host'] = $XCOW_B['this_host'];
				$bodyFiller['avatar'] = $mailInfo['photo'];
				$bodyFiller['avatar_url'] = $mailInfo['photo_url'];
				$bodyFiller['message'] = $mailInfo['reactDescription'];
				$bodyFiller['message_from'] = $userList[$mailInfo['reactUser']]['FirstName']." ".$userList[$mailInfo['reactUser']]['LastName'];
				$bodyFiller['actMessage'] = $mailInfo['actDescription'];
				$bodyFiller['actMessage_from'] = $userList[$mailInfo['actUser']]['FirstName']." ".$userList[$mailInfo['actUser']]['LastName'];
				$bodyFiller['actUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/act/view?act=".$this->id;
				
				$body = mail_template('3.3.react-update', $bodyFiller, $mailLanguageValue);

				// names AND emails are checked in sendMail
				if ($this->mode == "onlyDisplay") {
					$status = $body;
					break;
				}
				if ($this->mode == "send") {
					$sendStatus = sendMailWithHTML($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);
					log2file("Send React Mail to: ".$receiver_mail);

					if ($sendStatus == 0) { $status = language('sciomio_mail_user_status_wrong'); }
					if ($sendStatus == 1) { $status = language('sciomio_mail_user_status_ok'); }

				}
			}
		}
			
		# output
		$this->ses['response']['param']['status'] = $status;

     }
     
}

?>
