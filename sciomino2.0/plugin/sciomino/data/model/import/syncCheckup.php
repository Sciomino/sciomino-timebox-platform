<?

class importSyncCheckup extends control {

    function Run() {

        global $XCOW_B;

		$this->id = $this->ses['request']['param']['id'];
		if (! isset($this->id)) {$this->id = 0;}
		$this->debug = $this->ses['request']['param']['debug'];
		if (! isset($this->debug)) {$this->debug = 0;}

		# keep errors
		$this->userInfo = array();
		$this->syncLog = array();
		$this->syncErrors = array();
		
		# import
		if (! $XCOW_B['sciomino']['import-done'] && $this->id != "0") {

		# ok, first read mapping for this skin
		require($XCOW_B['sciomino']['skin-directory']."/".$XCOW_B['sciomino']['import-map-file']);
		$map = getImportMap();

		#
        # Construct QUERY
        #
        $where = "";
        $where = " WHERE ".$map['id']." like '".$this->id."'";

        $query = "SELECT * FROM ".$XCOW_B['sciomino']['import-update-table'].$where." ORDER BY id";

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if ($result && mysql_affected_rows($XCOW_B['mysql_link']) > 0) {

			while ($result_row = mysql_fetch_assoc($result)) {

				##############################
				# BEGIN: Same code as sync.php
				##############################
				
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
			
				# start
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

				##############################
				# END: Same code as sync.php
				##############################

				# do something with the user info
				$userName = $result_row[$map['user']];
				$userRef = getUserIdFromUserName($userName);

				if ( ! empty($userRef) ) {
					$this->userInfo = current(UserApiListUserByReference($userRef));
					
					# checkup
					# log formats: 
					# - $this->syncLog[] = "CATEGORY:MAP NAME:LOCAL VALUE:STATUS (0:error|1:ok|2:unknown):REMOTE NAME:REMOTE VALUE";
					# - $this->syncErrors[] = "CATEGORY\tKEY\tVALUE\tMATCHING VALUE\n";
					$this->syncLog[] = "CATEGORY:MAP NAME:LOCAL VALUE:STATUS (0:error|1:ok|2:unknown):REMOTE NAME:REMOTE VALUE";

					# - $this->user
					foreach ($this->user as $key => $val) {
						# tricky: reverse of resetUser
						# - map key of database field to key in import-map file
						$mapUser = array('firstName' => 'firstName','lastName' => 'lastName');
						$checkInfo = $this->checkUp(trim($map[$mapUser[$key]]),"user",$key,trim($val));
						if ($checkInfo[0] == 0) {
							$this->syncErrors[] = "USER\t$key\t$val\t".$checkInfo['value'];
						}
					}
					# - $this->annotation
					foreach ($this->annotation as $key => $val) {
						# tricky: reverse of resetAnnotation
						# - map key of database field to key in import-map file
						$mapAnnotation = array('title' => 'title','dateofbirthday' => 'dateOfBirthDay','dateofbirthmonth' => 'dateOfBirthMonth','dateofbirthyear' => 'dateOfBirthYear','gender' => 'gender','foto' => 'photo');
						$checkInfo = $this->checkUp(trim($map[$mapAnnotation[$key]]),"annotation",$key,trim($val));
						if ($checkInfo[0] == 0) {
							$this->syncErrors[] = "USER ANNOTATION\t$key\t$val\t".$checkInfo['value'];
						}
					}
					# - $this->contact (work)
					foreach ($this->contact['Work'] as $key => $val) {
						# tricky: reverse of resetContact
						# - map key of database field to key in import-map file
						$mapContact = array('email' => 'workEmail','telExtern' => 'workTelExtern','telIntern' => 'workTelIntern','telMobile' => 'workMobile','telLync' => 'workLync','telPager' => 'workPager','telFax' => 'workFax','pac' => 'workPac','myId' => 'workMyId','assistentId' => 'workAssistentId','managerId' => 'workManagerId');
						$checkInfo = $this->checkUp(trim($map[$mapContact[$key]]),"contact",$key,trim($val));
						if ($checkInfo[0] == 0) {
							$this->syncErrors[] = "USER CONTACT\t$key\t$val\t".$checkInfo['value'];
						}
					}
					# - $this->address (work)
					foreach ($this->address['Work'] as $key => $val) {
						# tricky: reverse of resetAddress
						# - map key of database field to key in import-map file
						$mapAddress = array('address' => 'workAddress','postalcode' => 'workPostalCode','city' => 'workCity','country' => 'workCountry');
						$checkInfo = $this->checkUp(trim($map[$mapAddress[$key]]),"address",$key,trim($val));
						if ($checkInfo[0] == 0) {
							$this->syncErrors[] = "USER ADDRESS\t$key\t$val\t".$checkInfo['value'];
						}
					}
					# - $this->organization
					foreach ($this->organization['Current'] as $key => $val) {
						# tricky: reverse of resetOrganization
						# - map key of database field to key in import-map file
						$mapOrganization = array('industry' => 'currentIndustry','company' => 'currentCompany','building' => 'currentBuilding','room' => 'currentRoom','role' => 'currentRole','division' => 'currentDivision','section' => 'currentSection','startDate' => 'currentStartDate','endDate' => 'currentEndDate','parttime' => 'currentParttime');
						$checkInfo = $this->checkUp(trim($map[$mapOrganization[$key]]),"organization",$key,trim($val));
						if ($checkInfo[0] == 0) {
							$this->syncErrors[] = "USER ORGANIZATION\t$key\t$val\t".$checkInfo['value'];
						}
					}

				}
				else {
					$this->syncErrors[] = "MAIN\tuser unknown";
				}

				# debug
				if ($this->debug > 0) {
					echo "\n###LOG###";
					foreach ($this->syncLog as $log) {
						echo "\n".$log;
					}
				}

				if ($this->debug > 1) {
					echo "\n### LOCAL ###\n";
					echo "\nUserName: ".$userName."<br/>";
					echo "\nUserRef: ".$userRef."<br/>";
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
					echo "\n### REMOTE ###\n";
					print_r($this->userInfo);
				}
				
			}
		}
		else {
			catchMysqlError("Sync", $XCOW_B['mysql_link']);
			$this->syncErrors[] = "MAIN\tmysql error / no such user in table";
		}
		}
		else {
			if ($this->id == "0") {
				$this->syncErrors[] = "MAIN\tno id, usage: syncCheckup?id=[ID]";
			}
			else {
				$this->syncErrors[] = "MAIN\timport not allowed";
			}
		}

		# output

		if (count($this->syncErrors) == 0) {
			$status = "OK";
		}
		else {
			$status = "ERROR:".count($this->syncErrors);

			$status .= "\n###ERRORS###";
			foreach ($this->syncErrors as $error) {
				$status .= "\n".$error;
			}
			if (count($this->syncLog) > 0) {
				$status .= "\n###LOG###";
				foreach ($this->syncLog as $log) {
					$status .= "\n".$log;
				}
			}

		}
		
		$this->ses['response']['param']['status'] = $status;

    }
    
    # checkstatus:
    # - 0: error, the value does not match
    # - 1: ok, the value matches
    # - 2: unknown, the value is not checked, because the user can edit it...
    # tricky:
    # - attribute is key of database field (local)
    # - checkValues[$attribute] is key of userInfo array (remote)
    function checkUp($item, $category, $attribute, $value) {
		$checkStatus = 0;
		$checkValue = "unknown";
		if ($item != '') {
			if ($category == "user") {
				$checkValues = array('firstName' => 'FirstName','lastName' => 'LastName');
				if (trim($this->userInfo[$checkValues[$attribute]]) == $value) {
					$checkStatus = 1;
					$checkValue = "match";
				}
				else {
					$checkValue = $this->userInfo[$checkValues[$attribute]];
				}
			}
			if ($category == "annotation") {
				$checkValues = array('title' => 'title','dateofbirthday' => 'dateofbirthday','dateofbirthmonth' => 'dateofbirthmonth','dateofbirthyear' => 'dateofbirthyear','gender' => 'gender','photo' => 'photo');
				if (trim($this->userInfo[$checkValues[$attribute]]) == $value) {
					$checkStatus = 1;
					$checkValue = "match";
				}
				else {
					$checkValue = $this->userInfo[$checkValues[$attribute]];
				}
			}
			if ($category == "contact") {
				$checkValues = array('email' => 'email','telExtern' => 'telExtern','telIntern' => 'telIntern','telMobile' => 'telMobile','telLync' => 'telLync','telPager' => 'telPager','telFax' => 'telFax','pac' => 'pac','myId' => 'myId','assistentId' => 'assistentId','managerId' => 'managerId');
				$contactId = get_id_from_multi_array($this->userInfo['Contact'], 'Name', 'Work');
				if (trim($this->userInfo['Contact'][$contactId][$checkValues[$attribute]]) == $value) {
					$checkStatus = 1;
					$checkValue = "match";
				}
				else {
					$checkValue = $this->userInfo['Contact'][$contactId][$checkValues[$attribute]];
				}
			}
			if ($category == "address") {
				$checkValues = array('address' => 'address','postalcode' => 'postalcode','city' => 'city','country' => 'country');
				$addressId = get_id_from_multi_array($this->userInfo['Address'], 'Name', 'Work');
				if (trim($this->userInfo['Address'][$addressId][$checkValues[$attribute]]) == $value) {
					$checkStatus = 1;
					$checkValue = "match";
				}
				else {
					$checkValue = $this->userInfo['Address'][$addressId][$checkValues[$attribute]];
				}
			}
			if ($category == "organization") {
				$checkValues = array('industry' => 'industry','company' => 'company','building' => 'building','room' => 'room','role' => 'role','division' => 'division','section' => 'section','startDate' => 'startDate','endDate' => 'endDate','parttime' => 'parttime');
				$organizationId = get_id_from_multi_array($this->userInfo['Organization'], 'Name', 'Current');
				if (trim($this->userInfo['Organization'][$organizationId][$checkValues[$attribute]]) == $value) {
					$checkStatus = 1;
					$checkValue = "match";
				}
				else {
					$checkValue = $this->userInfo['Organization'][$organizationId][$checkValues[$attribute]];
				}
			}
		}
		else {
			$checkStatus = 2;
		}
		
		$this->syncLog[] = $category.":".$item.":".$value.":".$checkStatus.":".$attribute.":".$checkValue;
		
		return array($checkStatus, $checkValue);
	}

}

?>
