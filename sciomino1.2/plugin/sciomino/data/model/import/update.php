<?

class importUpdate extends control {

    function Run() {

        global $XCOW_B;

		# usage
		# - ?mode=background&file=FILE 		(upload FILE and put it in the queue)
		# - ?mode=[new|update]&source=queue	(read FILE from queue, then process as NEW or UPDATE)
		# - ?mode=[new|update]&file=FILE 	(read FILE from filesystem, then process as NEW or UPDATE)
		# - ?mode=[new|update] 				(try to read file (=[prefix][yyyymmdd][ext]) from filesystem, then process as NEW or UPDATE)
		# - ?file=FILE 						(test if input file is correct and display lots of info)
		# - ?source=queue 					(test if queue is correct and display lots of info, note:queue is removed!)

		# file
		# - location of file
		# - OR the file itself
		$this->file = $this->ses['request']['param']['file'];

		# mode
		# - new: for session + user + SSO + knowledge & experiences
		#   valid actions: add (+ edit)
		# - update: for session + user + SSO [+ knowledge & experiences for 'add' action]
		# 	valid actions: add + edit + remove
		# - onlySSO: for session + SSO
		#	valid action: add + edit
		# - onlyDisplay: to show data in browser, don't store anything
		# - background: sync in background, using queue
		$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}

		# source
		# - read from a file
		# - OR from the queue
		$this->source = $this->ses['request']['param']['source'];
        if (! isset($this->source)) {$this->source = 'file';}

		# keep ww
		$this->userLines = array();
		$count = 0; 
	
		# import
		$file = "";
		$validXML = 0;
		if (! $XCOW_B['sciomino']['import-done']) {

			if ($this->mode == "background") {
				# upload file
				$this->file_tmp = $this->ses['request']['file_info']['file']['tmp_name'];
				$this->file_name = basename($this->ses['request']['file_info']['file']['name']);
				$this->file_size = $this->ses['request']['file_info']['file']['size'];

				$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
				$this->file_id = time();
				$this->file_upload_name = $this->file_id.".".$this->file_ext;
				$this->file_upload_location = $XCOW_B['upload_base']."/".$this->file_upload_name;

				# upload file to 'file_name_new'
				$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

				# if upload OK 
				# - TODO: check schema
				# - move file to 'destination'
				# - set queue entry
				if ($uploadStatus['status'] == 1) {
					
					$xmlReader = new XMLReader();
					if ($xmlReader->xml($this->file_upload_location)) {
						# 1. xml is supposed to be valid, if the XMLreader can read it
						$validXML = 1;
						/*
						# 2. now set schema and properties
						$xmlReader->setSchema("filename_of_schema.xsd");
						$xmlReader->setParserProperty(XMLReader::VALIDATE, true);
						# 3. if every read/next is ok, it's cool!
						while ($xmlReader->next()) {
							if (! $xmlReader->isValid()) {
								$validXML = 0;
								break;
							}
						}
						*/
					}
					if ($validXML) {
						moveUploadFile ($this->file_upload_location, $XCOW_B['sciomino']['import-update-directory'], $this->file_upload_name);

						setQueueEntry($this->file_upload_name, $this->file_upload_name);
						log2file("In Queue: ".$this->file_upload_name);
					}
					
				}
			}
			else {
				if ($this->source == "queue") {
					# read file location from queue
					$fileFromQueue = getQueueEntry();
					if ($fileFromQueue != 0) {
						log2file("Out Queue: ".$fileFromQueue);
						$file = $XCOW_B['sciomino']['import-update-directory']."/".$fileFromQueue;
					}
				}
				else {
					if (isset($this->file)) {
						# read file location from parameter
						$file = $XCOW_B['sciomino']['import-update-directory']."/".$this->file;
					}
					else { 
						# read file from predefined source: /[PREFIX][DATE=yyyymmdd][EXTENSION]
						$yesterdayStamp = time() - (24 * 60 * 60);
						$yesterday = date('Ymd', $yesterdayStamp);
						$file = $XCOW_B['sciomino']['import-update-directory']."/".$XCOW_B['sciomino']['import-update-file-prefix'].$yesterday.$XCOW_B['sciomino']['import-update-file-extension'];
					}
				}
			}
			
			if (file_exists($file)) {

				# ok, first read mapping for this skin
				require($XCOW_B['sciomino']['skin-directory']."/".$XCOW_B['sciomino']['import-map-file']);
				$map = getImportMap();

				# intentionally :-), no xml in the file, we can't deal with it...
				try { $xml = simplexml_load_file($file); } 
				catch (Exception $ignored) { } 

				// did we get xml?
				if (isset($xml)) {
					foreach ($xml->{$map['record']} as $data_record) {

						# user constructs
						$this->user = array();
						$this->annotation = array();
						$this->contact = array();
						$this->contact['Home'] = array();
						$this->contact['Work'] = array();
						$this->address = array();
						$this->address['Home'] = array();
						$this->address['Work'] = array();
						$this->organization = array();
						$this->organization['Current'] = array();
						$this->organization['Past'] = array();

						# reset data in update mode
						# - match the import-map to reset fields that are actually used
						# - note: these are the same fields as 'personalia-filled' in 'sciomino.ini'
						if ($this->mode == "update") {
							$resetUser = array('firstName' => 'firstName','lastName' => 'lastName');
							$resetAnnotation = array('title' => 'title','dateOfBirthDay' => 'dateofbirthday','dateOfBirthMonth' => 'dateofbirthmonth','dateOfBirthYear' => 'dateofbirthyear','gender' => 'gender','photo' => 'foto');
							$resetOrganization = array('currentIndustry' => 'industry','currentCompany' => 'company','currentBuilding' => 'building','currentRoom' => 'room','currentRole' => 'role','currentDivision' => 'division','currentSection' => 'section','currentStartDate' => 'startDate','currentEndDate' => 'endDate','currentParttime' => 'parttime');
							$resetContact = array('workEmail' => 'email','workTelExtern' => 'telExtern','workTelIntern' => 'telIntern','workMobile' => 'telMobile','workLync' => 'telLync','workPager' => 'telPager','workFax' => 'telFax','workPac' => 'pac','workMyId' => 'myId','workAssistentId' => 'assistentId','workManagerId' => 'managerId');
							$resetAddress = array('workAddress' => 'address','workPostalCode' => 'postalcode','workCity' => 'city','workCountry' => 'country');
							foreach ($resetUser as $key => $val) {
								if ($map[$key] != '') { $this->user[$val] = ""; }
							}
							foreach ($resetAnnotation as $key => $val) {
								if ($map[$key] != '') { $this->annotation[$val] = ""; }
							}
							foreach ($resetOrganization as $key => $val) {
								if ($map[$key] != '') { $this->organization['Current'][$val] = ""; }
							}
							foreach ($resetContact as $key => $val) {
								if ($map[$key] != '') { $this->contact['Work'][$val] = ""; }
							}
							foreach ($resetAddress as $key => $val) {
								if ($map[$key] != '') { $this->address['Work'][$val] = ""; }
							}

							# also, reset personal information
							# note: these remain editable on the user profile page and are not part of 'personalia-filled'
							$resetPersonalContact = array('homeEmail' => 'email','homeTel' => 'telHome','homeMobile' => 'telMobile');
							$resetPersonalAddress = array('homeAddress' => 'address','homePostalCode' => 'postalcode','homeCity' => 'city','homeCountry' => 'country');
							foreach ($resetPersonalContact as $key => $val) {
								if ($map[$key] != '') { $this->contact['Home'][$val] = ""; }
							}
							foreach ($resetPersonalAddress as $key => $val) {
								if ($map[$key] != '') { $this->address['Home'][$val] = ""; }
							}
						}
						
						# start
						echo "processing: ".$data_record->{$map['id']}."...";
						$this->annotation[$map['idName']] = (string) $data_record->{$map['id']};

						# personal information
						if (trim((string) $data_record->{$map['firstName']}) != '') {
							$this->user['firstName'] = (string) $data_record->{$map['firstName']};
						}
						if (trim((string) $data_record->{$map['lastName']}) != '') {
							$this->user['lastName'] = (string) $data_record->{$map['lastName']};
							if (trim((string) $data_record->{$map['middleName']}) != '') {
								$this->user['lastName'] = (string) $data_record->{$map['middleName']} ." ". (string) $data_record->{$map['lastName']};
							}
						}
						if (trim((string) $data_record->{$map['title']}) != '') {
							$this->annotation['title'] = (string) $data_record->{$map['title']};
						}
						if (trim((string) $data_record->{$map['gender']}) != '') {
							$this->annotation['gender'] = (string) $data_record->{$map['gender']};
						}
						if ($map['dateOfBirthAction'] == "substring") {
							$this->annotation['dateofbirthday'] = ltrim(substr($data_record->{$map['dateOfBirthDay']}, 6, 2), '0');
							$this->annotation['dateofbirthmonth'] = ltrim(substr($data_record->{$map['dateOfBirthMonth']}, 4, 2), '0');
							$this->annotation['dateofbirthyear'] = ltrim(substr($data_record->{$map['dateOfBirthYear']}, 0, 4), '0');
						}
						elseif ($map['dateOfBirthAction'] == "splitleft") {
							$dobDay = explode('-', $data_record->{$map['dateOfBirthDay']});
							$dobMonth = explode('-', $data_record->{$map['dateOfBirthMonth']});
							$dobYear = explode('-', $data_record->{$map['dateOfBirthYear']});
							$this->annotation['dateofbirthday'] = ltrim($dobDay[2], '0');
							$this->annotation['dateofbirthmonth'] = ltrim($dobMonth[1], '0');
							$this->annotation['dateofbirthyear'] = ltrim($dobYear[0], '0');
						}
						elseif ($map['dateOfBirthAction'] == "splitright") {
							$dobDay = explode('-', $data_record->{$map['dateOfBirthDay']});
							$dobMonth = explode('-', $data_record->{$map['dateOfBirthMonth']});
							$dobYear = explode('-', $data_record->{$map['dateOfBirthYear']});
							$this->annotation['dateofbirthday'] = ltrim($dobDay[0], '0');
							$this->annotation['dateofbirthmonth'] = ltrim($dobMonth[1], '0');
							$this->annotation['dateofbirthyear'] = ltrim($dobYear[2], '0');
						}
						else {
							if (trim((string) $data_record->{$map['dateOfBirthDay']}) != '') {
								$this->annotation['dateofbirthday'] = (string) $data_record->{$map['dateOfBirthDay']};
							}
							if (trim((string) $data_record->{$map['dateOfBirthMonth']}) != '') {
								$this->annotation['dateofbirthmonth'] = (string) $data_record->{$map['dateOfBirthMonth']};
							}
							if (trim((string) $data_record->{$map['dateOfBirthYear']}) != '') {
								$this->annotation['dateofbirthyear'] = (string) $data_record->{$map['dateOfBirthYear']};
							}
						}
						if (trim((string) $data_record->{$map['description']}) != '') {
							$this->annotation['description'] = (string) $data_record->{$map['description']};
						}
						$this->file_name = "";
						if (trim((string) $data_record->{$map['foto']}) != '') {
							$this->file_name = (string) $data_record->{$map['foto']};
							$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
							$this->file_id = md5(microtime().date("r").mt_rand(11111, 99999));
							$this->file_upload_name = $this->file_id.".".$this->file_ext;

							$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;
						}

						# personal contact
						if ( $XCOW_B['sciomino']['skin-privacy'] == "yes" ) {
							if (trim((string) $data_record->{$map['homeEmail']}) != '') {
								$this->contact['Home']['email'] = (string) $data_record->{$map['homeEmail']};
							}
							if (trim((string) $data_record->{$map['homeTel']}) != '') {
								$this->contact['Home']['telHome'] = (string) $data_record->{$map['homeTel']};
							}
							if (trim((string) $data_record->{$map['homeMobile']}) != '') {
								$this->contact['Home']['telMobile'] = (string) $data_record->{$map['homeMobile']};
							}
						}

						# personal address
						if ( $XCOW_B['sciomino']['skin-privacy'] == "yes" ) {
							if (trim((string) $data_record->{$map['homeAddress']}) != '') {
								$this->address['Home']['address'] = (string) $data_record->{$map['homeAddress']};
							}
							if (trim((string) $data_record->{$map['homePostalCode']}) != '') {
								$this->address['Home']['postalcode'] = (string) $data_record->{$map['homePostalCode']};
							}
						}
						if (trim((string) $data_record->{$map['homeCity']}) != '') {
							$this->address['Home']['city'] = (string) $data_record->{$map['homeCity']};
						}
						if ($map['homeCountryAction'] == "insert") {
							$this->address['Home']['country'] = $map['homeCountry'];
						}
						else {
							if (trim((string) $data_record->{$map['homeCountry']}) != '') {
								$this->address['Home']['country'] = (string) $data_record->{$map['homeCountry']};
								$this->address['Home']['country'] = strtolower($this->address['Home']['country']);
							}
						}

						# work
						if (trim((string) $data_record->{$map['currentIndustry']}) != '') {
							$this->organization['Current']['industry'] = (string) $data_record->{$map['currentIndustry']};
						}
						if (trim((string) $data_record->{$map['currentCompany']}) != '') {
							$this->organization['Current']['company'] = (string) $data_record->{$map['currentCompany']};
						}
						if (trim((string) $data_record->{$map['currentBuilding']}) != '') {
							$this->organization['Current']['building'] = (string) $data_record->{$map['currentBuilding']};
						}
						if (trim((string) $data_record->{$map['currentRoom']}) != '') {
							$this->organization['Current']['room'] = (string) $data_record->{$map['currentRoom']};
						}
						if (trim((string) $data_record->{$map['currentRole']}) != '') {
							$this->organization['Current']['role'] = (string) $data_record->{$map['currentRole']};
						}
						if (trim((string) $data_record->{$map['currentDivision']}) != '') {
							$this->organization['Current']['division'] = (string) $data_record->{$map['currentDivision']};
						}
						if (trim((string) $data_record->{$map['currentSection']}) != '') {
							$this->organization['Current']['section'] = (string) $data_record->{$map['currentSection']};
						}
						if (trim((string) $data_record->{$map['currentStartDate']}) != '') {
							$this->organization['Current']['startDate'] = (string) $data_record->{$map['currentStartDate']};
						}
						if (trim((string) $data_record->{$map['currentEndDate']}) != '') {
							$this->organization['Current']['endDate'] = (string) $data_record->{$map['currentEndDate']};
						}
						if (trim((string) $data_record->{$map['currentParttime']}) != '') {
							$this->organization['Current']['parttime'] = (string) $data_record->{$map['currentParttime']};
						}

						# work contact
						if (trim((string) $data_record->{$map['workEmail']}) != '') {
							$this->contact['Work']['email'] = (string) $data_record->{$map['workEmail']};
						}
						if (trim((string) $data_record->{$map['workTelExtern']}) != '') {
							$this->contact['Work']['telExtern'] = (string) $data_record->{$map['workTelExtern']};
						}
						if (trim((string) $data_record->{$map['workTelIntern']}) != '') {
							$this->contact['Work']['telIntern'] = (string) $data_record->{$map['workTelIntern']};
						}
						if (trim((string) $data_record->{$map['workMobile']}) != '') {
							$this->contact['Work']['telMobile'] = (string) $data_record->{$map['workMobile']};
						}
						if (trim((string) $data_record->{$map['workLync']}) != '') {
							$this->contact['Work']['telLync'] = (string) $data_record->{$map['workLync']};
						}
						if (trim((string) $data_record->{$map['workPager']}) != '') {
							$this->contact['Work']['telPager'] = (string) $data_record->{$map['workPager']};
						}
						if (trim((string) $data_record->{$map['workFax']}) != '') {
							$this->contact['Work']['telFax'] = (string) $data_record->{$map['workFax']};
						}
						if (trim((string) $data_record->{$map['workPac']}) != '') {
							$this->contact['Work']['pac'] = (string) $data_record->{$map['workPac']};
						}
						if (trim((string) $data_record->{$map['workMyId']}) != '') {
							$this->contact['Work']['myId'] = (string) $data_record->{$map['workMyId']};
						}
						if (trim((string) $data_record->{$map['workAssistentId']}) != '') {
							$this->contact['Work']['assistentId'] = (string) $data_record->{$map['workAssistentId']};
						}
						if (trim((string) $data_record->{$map['workManagerId']}) != '') {
							$this->contact['Work']['managerId'] = (string) $data_record->{$map['workManagerId']};
						}

						# work addrress
						if ($map['workCityAction'] == "split") {
							list($this->address['Work']['city'], $this->address['Work']['address']) = explode(",", $data_record->{$map['workCity']});
							$this->address['Work']['address'] = trim($this->address['Work']['address']);

							if (trim((string) $data_record->{$map['workPostalCode']}) != '') {
								$this->address['Work']['postalcode'] = (string) $data_record->{$map['workPostalCode']};
							}
						}
						else {
							if (trim((string) $data_record->{$map['workAddress']}) != '') {
								$this->address['Work']['address'] = (string) $data_record->{$map['workAddress']};
							}
							if (trim((string) $data_record->{$map['workPostalCode']}) != '') {
								$this->address['Work']['postalcode'] = (string) $data_record->{$map['workPostalCode']};
							}
							if (trim((string) $data_record->{$map['workCity']}) != '') {
								$this->address['Work']['city'] = (string) $data_record->{$map['workCity']};
							}
						}
						if ($map['workCountryAction'] == "insert") {
							$this->address['Work']['country'] = $map['workCountry'];
						}
						else {
							if (trim((string) $data_record->{$map['workCountry']}) != '') {
								$this->address['Work']['country'] = (string) $data_record->{$map['workCountry']};
								$this->address['Work']['country'] = strtolower($this->address['Work']['country']);
							}
						}

						# do something with the user info
						$addMore = 0;
						$this->userId = 0;

						$actie = (string) $data_record->{$map['action']};
						$userName = (string) $data_record->{$map['user']};
						$userRef = getUserIdFromUserName($userName);
						# action is remove
						if ($actie == $map['actionRemove']) {
							if ( ! empty($userRef) ) {
								# delete USER: only in update mode
								if ($this->mode == "update") {							
									# take care over userRef (in Session table from frontend) & userId (in User table from API)
									$this->userId = UserApiGetUserFromReference($userRef);

									# user should be removed from index first!
									UserApiListListDelete($this->userId);
															
									# delete from answers api
									# - first get answers from user
									# - second: delete answers
									$answersList = AnswersApiListActWithQuery("reference=".$userRef);
									foreach (array_keys($answersList) as $actId) {
										AnswersApiDeleteAct($actId);
									}
									
									# delete from user api
									UserApiDeleteUser($this->userId);

									# deactivate OR delete from session
									# - for now... delete, there is not an 'activate session' implemented in this script
									#deactivateSession($userName);
									registerDelete($userRef);					

									$this->userLines[] = $data_record->{$map['id']}."\t".$userName."\tremove\t".$this->user['firstName']." ".$this->user['lastName']."\t-\t-\t-\n";
								}
							}
							else {
								echo " *** USER UNKNOWN *** ";
							}
						}
						# action is add
						if ($actie == $map['actionAdd']) {
							# FIRST create user
							# - if user does not exist!
							if ( empty($userRef) ) {
								# save SESSION: for all modes
								if ($map['wachtwoordAction'] == "insert") {
									$userWW = $map['wachtwoord'];
								}
								elseif ($map['wachtwoordAction'] == "generate") {
									$userWW = generatePass();
								}
								else {
									if (trim((string) $data_record->{$map['wachtwoord']}) != '') {
										$userWW = (string) $data_record->{$map['wachtwoord']};
									}
								}
								$userEmail = (string) $data_record->{$map['email']};
								if ($this->mode != "onlyDisplay") {
									$activateKey = registerActivate($userName, md5($userWW), $userEmail);
									if ($XCOW_B['sciomino']['import-update-activate'] != "no") {
										activateSession($userName);
									} 
									$userRef = getUserIdFromUserName($userName);
									# create user in api
									$this->userId = UserApiCreateUser($this->user, $userRef);
								}
								$this->userLines[] = $data_record->{$map['id']}."\t".$userName."\tadd\t".$this->user['firstName']." ".$this->user['lastName']."\t".$userEmail."\t".$userWW."\t".$activateKey."\n";
							}

						}
						# action is add or edit
						if ($actie == $map['actionAdd'] || $actie == $map['actionEdit']) {
							# SECOND go...
							if ( ! empty($userRef) ) {
								# save USER: for new & update modes 
								if ($this->mode == "new" || $this->mode == "update") {
									# is user info saved by UserApiCreateUser? Otherwise update now
									if ($this->userId == 0) {
										$this->userId = UserApiUpdateUserByReference($this->user, $userRef);
									}
									else {
										# $this->userId is just created, so addMore if you like...
										$addMore = 1;
									}
									$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);
									$this->contactId = ScioMinoApiUpdateContact($this->contact, $this->userId);
									$this->addressId = ScioMinoApiUpdateAddress($this->address, $this->userId);
									$this->organizationId = ScioMinoApiUpdateOrganization($this->organization, $this->userId);

									# foto
									if ($this->file_name != '') {
										if (trim((string) $data_record->{$map['fotoStream']}) != '') {
											file_put_contents($XCOW_B['upload_destination_dir']."/".$this->file_upload_name, base64_decode(trim((string) $data_record->{$map['fotoStream']})));
										}
										else {
											copy ($XCOW_B['sciomino']['import-update-directory']."/".$this->file_name, $XCOW_B['upload_destination_dir']."/".$this->file_upload_name);
										}
										createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 96, 96);
										createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 48, 48);
										createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 32, 32);
				
										// reset original photo size
										if ($XCOW_B['sciomino']['original-photo-size'] != 0) {
											createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, $XCOW_B['sciomino']['original-photo-size'], $XCOW_B['sciomino']['original-photo-size']);
											moveUploadFile ($XCOW_B['upload_destination_dir']."/".$XCOW_B['sciomino']['original-photo-size']."x".$XCOW_B['sciomino']['original-photo-size']."_".$this->file_upload_name, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
										}

									}
								}

								$this->userLines[] = $data_record->{$map['id']}."\t".$userName."\tupdate\t".$this->user['firstName']." ".$this->user['lastName']."\t-\t-\t-\n";

								# save SSO: for all modes
								if ($map['displayNameAction'] == "fullName") {
									$displayName = $this->user['firstName']." ".$this->user['lastName'];
								}
								else {
									if (trim((string) $data_record->{$map['displayName']}) != '') {
										$displayName = (string) $data_record->{$map['displayName']};
									}
								}
								if ($this->mode != "onlyDisplay") {
									if ($displayName != '') {
											updateDisplayName($userRef, $displayName);
									}
									if ($map['remoteAccount'] != '') {
										if (trim((string) $data_record->{$map['remoteAccount']}) != '') {
											updateRemoteAuthentication($userRef, (string) $data_record->{$map['remoteAccount']});
										}
									}
								}

							}
							else {
								echo " *** USER UNKNOWN *** ";
							}

							# debug
							if ($this->mode == "onlyDisplay") {
								echo "<br/>\nACTIE: ".$actie."<br/>";
								echo "\nUserName: ".$userName."<br/>";
								echo "\nUserWW: ".$userWW."<br/>";
								echo "\nUserEmail: ".$userEmail."<br/>";
								echo "\nUserRef: ".$userRef."<br/>";
								echo "\n#Depend on UserRef<br/>";
								echo "\nUserId: ".$this->userId."<br/>";
								echo "\nremoteAccount: ".(string) $data_record->{$map['remoteAccount']}."<br/>";
								echo "\ndisplayName: ".$displayName."<br/>";
								echo "\nuser info<br/>";
								print_r($this->user);
								echo "\nextra info<br/>";
								print_r($this->annotation);
								echo "\ncontact info<br/>";
								print_r($this->contact);
								echo "\naddress info<br/>";
								print_r($this->address);
								echo "\norganization info<br/>";
								print_r($this->organization);
							}

						}

						# save KNOWLEDGE & EXPERIENCES: for newly created users
						if ($addMore || (! $addMore && $this->mode == "onlyDisplay")) {
							# add knowledge
							if ($map['knowledge'] != '') {
								foreach ($data_record->{$map['knowledge']} as $k) {
									$knowledge = array();
									$knowledge['field'] = (string) $k->{$map['knowledgeField']};
									$knowledge['field'] = ucfirst(strtolower($knowledge['field']));
									$knowledge['level'] = 1;
									if (trim((string) $k->{$map['knowledgeLevel']}) != '') {
										$knowledge['level'] = (string) $k->{$map['knowledgeLevel']};
									}
									if ($this->mode == "onlyDisplay") {
										print_r($knowledge);
									}
									else {
										$knowledgeId = ScioMinoApiSaveKnowledge($knowledge, $this->userId, '1');
									}
								}
							}
							# add hobby
							if ($map['hobby'] != '') {
								foreach ($data_record->{$map['hobby']} as $h) {
									$hobby = array();
									$hobby['field'] = (string) $h->{$map['hobbyField']};
									$hobby['field'] = ucfirst(strtolower($hobby['field']));
									if ($this->mode == "onlyDisplay") {
										print_r($hobby);
									}
									else {
										$hobbyId = ScioMinoApiSaveHobby($hobby, $this->userId, '1');
									}
								}
							}
							# add tag
							if ($map['tag'] != '') {
								foreach ($data_record->{$map['tag']} as $t) {
									$tag = array();
									$tag['name'] = (string) $t->{$map['tagName']};
									$tag['name'] = ucfirst(strtolower($tag['name']));
									if ($this->mode == "onlyDisplay") {
										print_r($tag);
									}
									else {
										$tagId = ScioMinoApiSaveTag($tag, $this->userId, '1');
									}
								}
							}
							# add experience-company
							if ($map['company'] != '') {
								foreach ($data_record->{$map['company']} as $e) {
									$experience = array();
									$experience['subject'] = (string) $e->{$map['companySubject']};
									$experience['title'] = (string) $e->{$map['companyTitle']};
									$experience['like'] = 2;
									if (trim((string) $e->{$map['companyLike']}) != '') {
										$experience['like'] = (string) $e->{$map['companyLike']};
									}
									if ($this->mode == "onlyDisplay") {
										print_r($experience);
									}
									else {
										$experienceId = ScioMinoApiSaveCompany($experience, $this->userId, '1');
									}
								}
							}
							# add experience-education
							if ($map['education'] != '') {
								foreach ($data_record->{$map['education']} as $e) {
									$experience = array();
									$experience['subject'] = (string) $e->{$map['educationSubject']};
									$experience['title'] = (string) $e->{$map['educationTitle']};
									if (trim((string) $e->{$map['educationPublisher']}) != '') {
										$experience['publisher'] = (string) $e->{$map['educationPublisher']};
									}
									if (trim((string) $e->{$map['educationRelation']}) != '') {
										$experience['relation-self'] = (string) $e->{$map['educationRelation']};
									}
									$experience['like'] = 2;
									if (trim((string) $e->{$map['educationLike']}) != '') {
										$experience['like'] = (string) $e->{$map['educationLike']};
									}
									if ($this->mode == "onlyDisplay") {
										print_r($experience);
									}
									else {
										$experienceId = ScioMinoApiSaveEducation($experience, $this->userId, '1');
									}
								}
							}
							# add experience-event
							if ($map['event'] != '') {
								foreach ($data_record->{$map['event']} as $e) {
									$experience = array();
									$experience['subject'] = (string) $e->{$map['eventSubject']};
									$experience['title'] = (string) $e->{$map['eventTitle']};
									if (trim((string) $e->{$map['eventPublisher']}) != '') {
										$experience['publisher'] = (string) $e->{$map['eventPublisher']};
									}
									if (trim((string) $e->{$map['eventRelation']}) != '') {
										$experience['relation-self'] = (string) $e->{$map['eventRelation']};
									}
									$experience['like'] = 2;
									if (trim((string) $e->{$map['eventLike']}) != '') {
										$experience['like'] = (string) $e->{$map['eventLike']};
									}
									if ($this->mode == "onlyDisplay") {
										print_r($experience);
									}
									else {
										$experienceId = ScioMinoApiSaveEvent($experience, $this->userId, '1');
									}
								}
							}
							# add experience-product
							if ($map['product'] != '') {
								foreach ($data_record->{$map['product']} as $e) {
									$experience = array();
									$experience['subject'] = (string) $e->{$map['productSubject']};
									$experience['title'] = (string) $e->{$map['productTitle']};
									if (trim((string) $e->{$map['productAlternative']}) != '') {
										$experience['alternative'] = (string) $e->{$map['productAlternative']};
									}
									$experience['like'] = 2;
									if (trim((string) $e->{$map['productLike']}) != '') {
										$experience['like'] = (string) $e->{$map['productLike']};
									}
									$experience['has'] = 1;
									if ($this->mode == "onlyDisplay") {
										print_r($experience);
									}
									else {
										$experienceId = ScioMinoApiSaveProduct($experience, $this->userId, '1');
									}
								}
							}
						}

						#end & settle down
						echo " done<br/>\n";
						$count++;
						usleep(100000);
						if ($this->mode == "onlyDisplay") {
							if ($count > 9) {break;}
						}
					}

					$status = "Import done. ".$count." records";
				}
				else {
					$status = "Import failure: xml error";
				}
			}
			else {
				if ($this->mode == "background") {
					if ($uploadStatus['status'] == 1) {
						if ($validXML) {
							$status = "Import ok: File uploaded and valid, will be processed soon.";
						}
						else {
							$status = "Import failure: File is not valid XML!";
						}
					}
					else {
						$status = "Import failure: File is not uploaded";
					}
				}
				else {
					$status = "Import failure: no such file";
				}
			}
		
		}
		else {
			$status = "Import not allowed";
		}

		# output
		$this->ses['response']['param']['status'] = $status;

		if (count($this->userLines) > 0) {
			# write new users to file...
			$file = $XCOW_B['log_dir']."/new-users.csv.".time();

			$fp = @fopen($file, "w");

			if ($fp) {
					foreach ($this->userLines as $line) {
						fwrite($fp, $line);
					}
					fclose($fp);
			}
		}
	}
}

?>
