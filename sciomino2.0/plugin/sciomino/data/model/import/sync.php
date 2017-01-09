<?

class importSync extends control {

    function Run() {

        global $XCOW_B;

		# mode
		# - all: do sync all
		# - onlyDisplay: to show data in browser, don't store anything
		# - background: sync in background, using queue
		# stamp
		# - day: sync all from yesterday
		# - week: sync all from last week	
		# - month: sync all from last month	
		# - queue: sync all from queue
		# - id: sync only one id
		# entries
		# - INT: number of queue entries to be synced 
		$this->mode = $this->ses['request']['param']['mode'];
		if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		$this->stamp = $this->ses['request']['param']['stamp'];
		$this->id = $this->ses['request']['param']['id'];
		if (! isset($this->id)) {$this->id = 0;}
		$this->entries = $this->ses['request']['param']['entries'];
		if (! isset($this->entries)) {$this->entries = 1;}
		$this->offset = $this->ses['request']['param']['offset'];
		if (! isset($this->offset)) {$this->offset = 0;}
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 0;}

		# keep ww
		$this->userLines = array();
		$count = 0; 
		
		# import
		if (! $XCOW_B['sciomino']['import-done']) {

		# ok, first read mapping for this skin
		require($XCOW_B['sciomino']['skin-directory']."/".$XCOW_B['sciomino']['import-map-file']);
		$map = getImportMap();

		#
        # Construct QUERY
        #
        $where = "";
   		if (isset($this->stamp)) {
			
			# what day stamp
			$dayStamp = 0;
			if ($this->stamp == "day") {
				$dayStamp = time() - (24 * 60 * 60);
			}
			elseif ($this->stamp == "week") {
				$dayStamp = time() - (24 * 60 * 60 * 7);
			}
			elseif ($this->stamp == "month") {
				$dayStamp = time() - (24 * 60 * 60 * 30);
			}

			# day stamp OR queue
			if ($dayStamp != 0) {
				$timeWarp = date('Y-m-d 00:00:00', $dayStamp);
				$where = " WHERE ".$map['actionDate']." > \"".$timeWarp."\"";
			}
			else {
				if ($this->stamp == "queue") {
					$mapIdFromQueueList = array();
					# read x entries at a time (if available)
					for ($i=0; $i<$this->entries; $i++) {
						$mapIdFromQueue = getQueueEntry();
						if ($mapIdFromQueue != 0) {
							$mapIdFromQueueList[] = $mapIdFromQueue;
						}
						else {
							break;
						}
					}
					if (count($mapIdFromQueueList) != 0) {
						$mapIdString = implode(",", $mapIdFromQueueList);
						$where = " WHERE ".$map['id']." IN (".$mapIdString.")";
					}
					else {
						# no queue, no match...
						$where = " WHERE 1 = 2";
						echo " *** Nothing In Queue: Do Nothing *** ";

					}
				}
				elseif ($this->stamp == "id") {
					$where = " WHERE ".$map['id']." like '".$this->id."'";
				}
				else {
					# no valid stamp, no match...
					$where = " WHERE 1 = 2";
					echo " *** No Valid Stamp: Do Nothing *** ";
				}
			}			
		}

        $query = "SELECT * FROM ".$XCOW_B['sciomino']['import-update-table'].$where." ORDER BY id";
        if ($this->limit != 0) {
			$query .= " LIMIT ".$this->offset.",".$this->limit;
		}
 
		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if ($result) {
			while ($result_row = mysql_fetch_assoc($result)) {
				#$userId = $result_row[$map['id']];

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

				# reset data in all mode
				# - match the import-map to reset fields that are actually used
				# - note: these are the same fields as 'personalia-filled' in 'sciomino.ini'
				if ($this->mode == "all") {
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
				echo "processing: ".$result_row[$map['id']]."...";
				$this->annotation[$map['idName']] = $result_row[$map['id']];

				# personal information
				if (trim($result_row[$map['firstName']]) != '') {
					$this->user['firstName'] = $result_row[$map['firstName']];
				}
				if (trim($result_row[$map['lastName']]) != '') {
					$this->user['lastName'] = $result_row[$map['lastName']];
					if (trim($result_row[$map['middleName']]) != '') {
						$this->user['lastName'] = $result_row[$map['middleName']] ." ". $result_row[$map['lastName']];
					}
				}
				if (trim($result_row[$map['title']]) != '') {
					$this->annotation['title'] = $result_row[$map['title']];
				}
				if (trim($result_row[$map['gender']]) != '') {
					$this->annotation['gender'] = $result_row[$map['gender']];
				}
				if ($map['dateOfBirthAction'] == "substring") {
					$this->annotation['dateofbirthday'] = ltrim(substr($result_row[$map['dateOfBirthDay']], 6, 2), '0');
					$this->annotation['dateofbirthmonth'] = ltrim(substr($result_row[$map['dateOfBirthMonth']], 4, 2), '0');
					$this->annotation['dateofbirthyear'] = ltrim(substr($result_row[$map['dateOfBirthYear']], 0, 4), '0');
				}
				elseif ($map['dateOfBirthAction'] == "splitleft") {
					$dobDay = explode('-', $result_row[$map['dateOfBirthDay']]);
					$dobMonth = explode('-', $result_row[$map['dateOfBirthMonth']]);
					$dobYear = explode('-', $result_row[$map['dateOfBirthYear']]);
					$this->annotation['dateofbirthday'] = ltrim($dobDay[2], '0');
					$this->annotation['dateofbirthmonth'] = ltrim($dobMonth[1], '0');
					$this->annotation['dateofbirthyear'] = ltrim($dobYear[0], '0');
				}
				elseif ($map['dateOfBirthAction'] == "splitright") {
					$dobDay = explode('-', $result_row[$map['dateOfBirthDay']]);
					$dobMonth = explode('-', $result_row[$map['dateOfBirthMonth']]);
					$dobYear = explode('-', $result_row[$map['dateOfBirthYear']]);
					$this->annotation['dateofbirthday'] = ltrim($dobDay[0], '0');
					$this->annotation['dateofbirthmonth'] = ltrim($dobMonth[1], '0');
					$this->annotation['dateofbirthyear'] = ltrim($dobYear[2], '0');
				}
				else {
					if (trim($result_row[$map['dateOfBirthDay']]) != '') {
						$this->annotation['dateofbirthday'] = $result_row[$map['dateOfBirthDay']];
					}
					if (trim($result_row[$map['dateOfBirthMonth']]) != '') {
						$this->annotation['dateofbirthmonth'] = $result_row[$map['dateOfBirthMonth']];
					}
					if (trim($result_row[$map['dateOfBirthYear']]) != '') {
						$this->annotation['dateofbirthyear'] = $result_row[$map['dateOfBirthYear']];
					}
				}
				if (trim($result_row[$map['description']]) != '') {
					$this->annotation['description'] = $result_row[$map['description']];
				}
				$this->file_name = "";
				if (trim($result_row[$map['foto']]) != '') {
					$this->file_name = $result_row[$map['foto']];
					$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
					$this->file_id = md5(microtime().date("r").mt_rand(11111, 99999));
					$this->file_upload_name = $this->file_id.".".$this->file_ext;

					$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;
				}

				# personal contact
				if ( $XCOW_B['sciomino']['skin-privacy'] == "yes" ) {
					if (trim($result_row[$map['homeEmail']]) != '') {
						$this->contact['Home']['email'] = $result_row[$map['homeEmail']];
					}
					if (trim($result_row[$map['homeTel']]) != '') {
						$this->contact['Home']['telHome'] = $result_row[$map['homeTel']];
					}
					if (trim($result_row[$map['homeMobile']]) != '') {
						$this->contact['Home']['telMobile'] = $result_row[$map['homeMobile']];
					}
				}

				# personal address
				if ( $XCOW_B['sciomino']['skin-privacy'] == "yes" ) {
					if (trim($result_row[$map['homeAddress']]) != '') {
						$this->address['Home']['address'] = $result_row[$map['homeAddress']];
					}
					if (trim($result_row[$map['homePostalCode']]) != '') {
						$this->address['Home']['postalcode'] = $result_row[$map['homePostalCode']];
					}
				}
				if (trim($result_row[$map['homeCity']]) != '') {
					$this->address['Home']['city'] = $result_row[$map['homeCity']];
				}
				if ($map['homeCountryAction'] == "insert") {
					$this->address['Home']['country'] = $map['homeCountry'];
				}
				else {
					if (trim($result_row[$map['homeCountry']]) != '') {
						$this->address['Home']['country'] = $result_row[$map['homeCountry']];
						$this->address['Home']['country'] = strtolower($this->address['Home']['country']);
					}
				}

				# work
				if (trim($result_row[$map['currentIndustry']]) != '') {
					$this->organization['Current']['industry'] = $result_row[$map['currentIndustry']];
				}
				if (trim($result_row[$map['currentCompany']]) != '') {
					$this->organization['Current']['company'] = $result_row[$map['currentCompany']];
				}
				if (trim($result_row[$map['currentBuilding']]) != '') {
					$this->organization['Current']['building'] = $result_row[$map['currentBuilding']];
				}
				if (trim($result_row[$map['currentRoom']]) != '') {
					$this->organization['Current']['room'] = $result_row[$map['currentRoom']];
				}
				if (trim($result_row[$map['currentRole']]) != '') {
					$this->organization['Current']['role'] = $result_row[$map['currentRole']];
				}
				if (trim($result_row[$map['currentDivision']]) != '') {
					$this->organization['Current']['division'] = $result_row[$map['currentDivision']];
				}
				if (trim($result_row[$map['currentSection']]) != '') {
					$this->organization['Current']['section'] = $result_row[$map['currentSection']];
				}
				if (trim($result_row[$map['currentStartDate']]) != '') {
					$this->organization['Current']['startDate'] = $result_row[$map['currentStartDate']];
				}
				if (trim($result_row[$map['currentEndDate']]) != '') {
					$this->organization['Current']['endDate'] = $result_row[$map['currentEndDate']];
				}
				if (trim($result_row[$map['currentParttime']]) != '') {
					$this->organization['Current']['parttime'] = $result_row[$map['currentParttime']];
				}

				# work contact
				if (trim($result_row[$map['workEmail']]) != '') {
					$this->contact['Work']['email'] = $result_row[$map['workEmail']];
				}
				if (trim($result_row[$map['workTelExtern']]) != '') {
					$this->contact['Work']['telExtern'] = $result_row[$map['workTelExtern']];
				}
				if (trim($result_row[$map['workTelIntern']]) != '') {
					$this->contact['Work']['telIntern'] = $result_row[$map['workTelIntern']];
				}
				if (trim($result_row[$map['workMobile']]) != '') {
					$this->contact['Work']['telMobile'] = $result_row[$map['workMobile']];
				}
				if (trim($result_row[$map['workLync']]) != '') {
					$this->contact['Work']['telLync'] = $result_row[$map['workLync']];
				}
				if (trim($result_row[$map['workPager']]) != '') {
					$this->contact['Work']['telPager'] = $result_row[$map['workPager']];
				}
				if (trim($result_row[$map['workFax']]) != '') {
					$this->contact['Work']['telFax'] = $result_row[$map['workFax']];
				}
				if (trim($result_row[$map['workPac']]) != '') {
					$this->contact['Work']['pac'] = $result_row[$map['workPac']];
				}
				if (trim($result_row[$map['workMyId']]) != '') {
					$this->contact['Work']['myId'] = $result_row[$map['workMyId']];
				}
				if (trim($result_row[$map['workAssistentId']]) != '') {
					if ($result_row[$map['workAssistentId']] != 0) {
						$this->contact['Work']['assistentId'] = $result_row[$map['workAssistentId']];
					}
				}
				if (trim($result_row[$map['workManagerId']]) != '') {
					if ($result_row[$map['workManagerId']] != 0) {
						$this->contact['Work']['managerId'] = $result_row[$map['workManagerId']];
					}
				}

				# work addrress
				if ($map['workCityAction'] == "split") {
					list($this->address['Work']['city'], $this->address['Work']['address']) = explode(",", $result_row[$map['workCity']]);
					$this->address['Work']['address'] = trim($this->address['Work']['address']);

					if (trim($result_row[$map['workPostalCode']]) != '') {
						$this->address['Work']['postalcode'] = $result_row[$map['workPostalCode']];
					}
				}
				else {
					if (trim($result_row[$map['workAddress']]) != '') {
						$this->address['Work']['address'] = $result_row[$map['workAddress']];
					}
					if (trim($result_row[$map['workPostalCode']]) != '') {
						$this->address['Work']['postalcode'] = $result_row[$map['workPostalCode']];
					}
					if (trim($result_row[$map['workCity']]) != '') {
						$this->address['Work']['city'] = $result_row[$map['workCity']];
					}
				}
				if ($map['workCountryAction'] == "insert") {
					$this->address['Work']['country'] = $map['workCountry'];
				}
				else {
					if (trim($result_row[$map['workCountry']]) != '') {
						$this->address['Work']['country'] = $result_row[$map['workCountry']];
						$this->address['Work']['country'] = strtolower($this->address['Work']['country']);
					}
				}

				# do something with the user info
				$this->userId = 0;
				$userName = $result_row[$map['user']];
				$userRef = getUserIdFromUserName($userName);
				
				# action is defined by boolean
				# $actie = $result_row[$map['action']];
				$actie = "none";
				# must have an id. (remoteAccount not necessary, this enables display-only accounts)
				if (trim($result_row[$map['id']]) != '') {
					$actie_bool = $result_row[$map['actionBool']];
					if ( ! empty($userRef) ) { 
						if ($actie_bool == $map['actionBoolYes']) {
							$actie = $map['actionEdit'];
						}
						else {
							$actie = $map['actionRemove'];
						}
					}
					else {
						if ($actie_bool == $map['actionBoolYes']) {
							$actie = $map['actionAdd'];
						}
					}
				}
					
				# action is none
				if ($actie == "none") {
					echo " *** Do Nothing *** ";
				}
				# action is remove
				if ($actie == $map['actionRemove']) {
					if ( ! empty($userRef) ) {
						# delete USER: only in all mode
						if ($this->mode == "all") {
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

							$this->userLines[] = $data_record->{$map['id']}."\t".$userName."\tremove\t".$this->user['firstName']." ".$this->user['lastName']."\t-\t-\n";
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
						# save SESSION: for all mode
						if ($map['wachtwoordAction'] == "insert") {
							$userWW = $map['wachtwoord'];
						}
						elseif ($map['wachtwoordAction'] == "generate") {
							$userWW = generatePass();
						}
						else {
							if (trim($result_row[$map['wachtwoord']]) != '') {
								$userWW = $result_row[$map['wachtwoord']];
							}
						}
						$userEmail = $result_row[$map['email']];
						if ($this->mode == "all") {
							registerActivate($userName, md5($userWW), $userEmail);
							activateSession($userName);
							$userRef = getUserIdFromUserName($userName);
							# create user in api
							$this->userId = UserApiCreateUser($this->user, $userRef);
						}
						$this->userLines[] = $data_record->{$map['id']}."\t".$userName."\tadd\t".$this->user['firstName']." ".$this->user['lastName']."\t".$userEmail."\t".$userWW."\n";
					}

				}
				# action is add or edit
				if ($actie == $map['actionAdd'] || $actie == $map['actionEdit']) {
					# SECOND go...
					if ( ! empty($userRef) ) {
						# save USER: for all mode
						if ($this->mode == "all") {
							# is user info saved by UserApiCreateUser? Otherwise update now
							if ($this->userId == 0) {
								$this->userId = UserApiUpdateUserByReference($this->user, $userRef);
							}
							else {
								# $this->userId is just created		
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

						$this->userLines[] = $data_record->{$map['id']}."\t".$userName."\tupdate\t".$this->user['firstName']." ".$this->user['lastName']."\t-\t-\n";

						# save SSO: for all mode
						if ($map['displayNameAction'] == "fullName") {
							$displayName = $this->user['firstName']." ".$this->user['lastName'];
						}
						else {
							if (trim($result_row[$map['displayName']]) != '') {
								$displayName = $result_row[$map['displayName']];
							}
						}
						if ($this->mode == "all") {
							if ($displayName != '') {
									updateDisplayName($userRef, $displayName);
							}
							if ($map['remoteAccount'] != '') {
								// always update remote account, even if it is empty
								updateRemoteAuthentication($userRef, $result_row[$map['remoteAccount']]);
								// if (trim($result_row[$map['remoteAccount']]) != '') {
								// 	updateRemoteAuthentication($userRef, $result_row[$map['remoteAccount']]);
								// }
							}
						}

					}
					else {
						echo " *** USER UNKNOWN *** ";
					}
				}

				# action not is none
				if ($actie != "none") {

					# debug
					if ($this->mode == "onlyDisplay") {
						echo "<br/>\nACTIE: ".$actie."<br/>";
						echo "\nUserName: ".$userName."<br/>";
						echo "\nUserWW: ".$userWW."<br/>";
						echo "\nUserEmail: ".$userEmail."<br/>";
						echo "\nUserRef: ".$userRef."<br/>";
						echo "\n#Depend on UserRef<br/>";
						echo "\nUserId: ".$this->userId."<br/>";
						echo "\nremoteAccount: ".$result_row[$map['remoteAccount']]."<br/>";
						echo "\ndisplayName: ".$this->user['firstName']." ".$this->user['lastName']."<br/>";
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

					# put in background
					if ($this->mode == "background") {
						#echo "\nSyncId: ".$result_row[$map['id']]."<br/>";
						setQueueEntry($result_row[$map['id']], $result_row[$map['id']]);
						log2file("In Queue: ".$result_row[$map['id']]);
					}
					if ($this->mode == "all") {
						if ($this->stamp == "queue") {
							log2file("Out Queue: ".$actie.": ".$result_row[$map['id']]);
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
			catchMysqlError("Sync", $XCOW_B['mysql_link']);
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
