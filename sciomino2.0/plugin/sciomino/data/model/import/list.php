<?

########################
# OBSOLETE             #
# moved to api:        #
# group/updateType.php #
########################

class importList extends control {

    function Run() {

        global $XCOW_B;

		# mode
		# - all: do import all
		# - onlyDisplay: to show data in browser, don't store anything
		$this->mode = $this->ses['request']['param']['mode'];
		if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		$this->offset = $this->ses['request']['param']['offset'];
		if (! isset($this->offset)) {$this->offset = 0;}
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 0;}
		
		$this->list = array();
		$count = 0;

		# import
		if (! $XCOW_B['sciomino']['import-done']) {

			# ok, first read data:
			# - id
			# - name
			# - ref
			# - manager id
			# - manager name
			# - manager ref
			
			# map
			require($XCOW_B['sciomino']['skin-directory']."/".$XCOW_B['sciomino']['import-map-file']);
			$map = getImportMap();

			# sync tabel
			$query = "SELECT * FROM ".$XCOW_B['sciomino']['import-update-table'].$where." ORDER BY id";
			if ($this->limit != 0) {
				$query .= " LIMIT ".$this->offset.",".$this->limit;
			}

			$result = mysql_query("$query", $XCOW_B['mysql_link']);

			if ($result) {
				while ($result_row = mysql_fetch_assoc($result)) {
					# only when 'opnemen=1'
					if ($result_row[$map['actionBool']] == $map['actionBoolYes']) {

						$id = $result_row[$map['id']];
						$this->list[$id] = array();
						
						# userRef (based on userName/loginName)
						$userName = $result_row[$map['user']];
						$this->list[$id]['userRef'] = getUserIdFromUserName($userName);
						
						# personal information
						if (trim($result_row[$map['firstName']]) != '') {
							$firstName = $result_row[$map['firstName']];
						}
						if (trim($result_row[$map['lastName']]) != '') {
							$lastName = $result_row[$map['lastName']];
							if (trim($result_row[$map['middleName']]) != '') {
								$lastName  = $result_row[$map['middleName']] ." ". $lastName ;
							}
						}
						$this->list[$id]['name'] = $firstName. " ".$lastName;
									
						if (trim($result_row[$map['workManagerId']]) != '') {
							if ($result_row[$map['workManagerId']] != 0) {
								$this->list[$id]['manId'] = $result_row[$map['workManagerId']];
							}
						}
					
					}
				}
			}

			# add manager name and -ref if the manager name exists
			foreach ($this->list as $key => $val) {
				if (isset ($this->list[$val['manId']]['name'])) {
					$this->list[$key]['manName'] = $this->list[$val['manId']]['name'];
					$this->list[$key]['manRef'] = $this->list[$val['manId']]['userRef'];
				}
			}
			
			# here we go
			echo "> processing...";
			foreach ($this->list as $key => $val) {
				if (isset($val['manName']) && isset($val['manRef']) ) {
					# userId (save for further use)
					if (! isset($this->list[$key]['userId'])) {
						$this->list[$key]['userId'] = UserApiGetUserFromReference($val['userRef']);
					}
					# - this user might be a retired manager, so delete his group
					if (! isset($this->list[$key]['groupId'])) {
						# delete existing group
						# - fortunately, when a user is deleted, his groups are automatically deleted,
						# - so only deleteing existing groups is sufficient :-)
						$oldUserGroup = UserApiGroupListWithQuery("type=manager&userId=".$this->list[$key]['userId']);
						if (count($oldUserGroup) > 0) {
							$oldUserGroup = current($oldUserGroup);
							if ($this->mode == "onlyDisplay") {
								echo "<br/>\nDelete Old User Group: ".$oldUserGroup['Name']."<br/>";
							}
							else {
								# users are deleted bij 'foreign key' in DB
								UserApiGroupDelete($oldUserGroup['Id']);
							}
						}
					}
					# groupId (save for further use)
					# - the group of the user's manager is deleted and recreated
					if (! isset($this->list[$val['manId']]['groupId'])) {
						# manager userId (save for further use)
						if (! isset($this->list[$val['manId']]['userId'])) {
							$this->list[$val['manId']]['userId'] = UserApiGetUserFromReference($val['manRef']);
						}

						# delete existing manager group
						$oldGroup = UserApiGroupListWithQuery("type=manager&userId=".$this->list[$val['manId']]['userId']);
						if (count($oldGroup) > 0) {
							$oldGroup = current($oldGroup);
							if ($this->mode == "onlyDisplay") {
								echo "<br/>\nDelete Old Group: ".$oldGroup['Name']."<br/>";
							}
							else {
								# users are deleted bij 'foreign key' in DB
								UserApiGroupDelete($oldGroup['Id']);
							}
						}
						
						# save new group
						if ($this->mode == "onlyDisplay") {
							echo "<br/>\nAdd New Group: ".$val['manName']."<br/>";
							# dummy groupId for better output in debug mode
							$groupId = "[NEW_ID]";
						}
						else {
							$newGroup = array();
							$newGroup['name'] = $val['manName'];
							$newGroup['type'] = "manager";
							$groupId = UserApiGroupSave($newGroup, $this->list[$val['manId']]['userId'], '1');
							# manager is part of his/her own group
							UserApiGroupSaveUser($groupId, $this->list[$val['manId']]['userId']);
						}	
						
						$this->list[$val['manId']]['groupId'] = $groupId;
						
						$count++;

					}
					# add user to group
					if ($this->mode == "onlyDisplay") {
						echo "<br/>\nAdd User: ".$this->list[$key]['userId']." (".$this->list[$key]['name'].")<br/>";
						echo "To Group: ".$this->list[$val['manId']]['groupId']." (".$this->list[$key]['manName'].")<br/>";
					}
					else {
						UserApiGroupSaveUser($this->list[$val['manId']]['groupId'], $this->list[$key]['userId']);
					}
					
					usleep(1000);

				}

				if ($this->mode == "onlyDisplay") {
					if ($count > 9) {break;}
				}
			
			}

			$status = "> Import done. ".$count." lijsten";
		
		}
		else {
			$status = "Import not allowed";
		}

		# output
		$this->ses['response']['param']['status'] = $status;

    }

}

?>
