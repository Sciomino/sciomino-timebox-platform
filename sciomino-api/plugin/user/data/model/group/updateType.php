<?

class groupUpdateType extends control {

    function Run() {

        global $XCOW_B;

		# type
		# - manager: manager lists
		# match
		# - how to identify the type? Should be unique!
		# - for now it is an UserAttribute...
		$this->type = $this->ses['request']['param']['type'];
		if (! isset($this->type)) {$this->type = 'manager';}
		$this->match = $this->ses['request']['param']['match'];
		if (! isset($this->match)) {$this->match = 'personeelsnummer';}
		
		$this->list = array();
		$groupsList = array();

		# ok, first read data:
		# - id (= match)
		# - name
		# - userId
		# - type Id
		# - type name
		# - type userId

		# read primary data from UserList
		$table = "User";
		$where = "";
		$order = "";
		$limit = "";
		$expand = 0;
		$userList = UserListWithValues($table, $where, $order, $limit, $expand);
		#print_r($userList);
		
		foreach ($userList as $user) {

			$match = $user['annotation'][get_id_from_multi_array($user['annotation'], 'name', $this->match)]['value'];
			if (isset($match)) {
				$id = $match;
				$this->list[$id] = array();
				
				# userId
				$this->list[$id]['userId'] = $user['userId'];
				
				# personal information
				$this->list[$id]['name'] = trim($user['userFirstName']." ".$user['userLastName']);
							
				# manager type
				if ($this->type == "manager") {
					$contactList = UserSectionList('contact', $user['userId']);
					$contactId = get_id_from_multi_array($contactList, 'name', 'Work');
					$typeMatch = $contactList[$contactId]['annotation'][get_id_from_multi_array($contactList[$contactId]['annotation'], 'name', 'managerId')]['value'];
					if (isset($typeMatch)) {
						if ($typeMatch != 0) {
							$this->list[$id]['typeId'] = $typeMatch;
						}
					}
				}	
			}		
		}

		# add type name and -userId if the type name exists
		foreach ($this->list as $key => $val) {
			if (isset ($this->list[$val['typeId']]['name'])) {
				$this->list[$key]['typeName'] = $this->list[$val['typeId']]['name'];
				$this->list[$key]['typeUserId'] = $this->list[$val['typeId']]['userId'];
			}
		}
		
		# here we go
		# - only when typeName is set, there is a group member
		foreach ($this->list as $key => $val) {
			if (isset($val['typeName']) && isset($val['typeUserId']) ) {

				# - this user might be a retired manager, so delete his group
				if (! isset($this->list[$key]['groupId'])) {
					# delete existing group
					# - fortunately, when a user is deleted, his groups are automatically deleted,
					# - so only deleteing existing groups is sufficient :-)
					$table = "UserGroup";
					$where = "WHERE UserGroupType = '".$this->type."' AND UserId = ".$val['userId'];
					$order = "";
					$limit = "";
					$expand = 0;
					$oldUserGroup = UserGroupListWithValues($table, $where, $order, $limit, $expand);
					if (count($oldUserGroup) > 0) {
						$oldUserGroup = current($oldUserGroup);
						$oldUserGroupId = array();
						$oldUserGroupId[] = $oldUserGroup['id'];
						UserGroupDelete($oldUserGroupId);
						# note: users are deleted bij 'foreign key' in DB
					}
				}

				# groupId (save for further use)
				if (! isset($this->list[$val['typeId']]['groupId'])) {

					# delete existing group
					$table = "UserGroup";
					$where = "WHERE UserGroupType = '".$this->type."' AND UserId = ".$val['typeUserId'];
					$order = "";
					$limit = "";
					$expand = 0;
					$oldGroup = UserGroupListWithValues($table, $where, $order, $limit, $expand);
					if (count($oldGroup) > 0) {
						$oldGroup = current($oldGroup);
						$oldGroupId = array();
						$oldGroupId[] = $oldGroup['id'];
						UserGroupDelete($oldGroupId);
						# note: users are deleted bij 'foreign key' in DB
					}
					
					# add new group
					$newGroup = array();
					$newGroup['name'] = $val['typeName'];
					$newGroup['type'] = $this->type;
					$groupId = UserGroupInsert($newGroup, $val['typeUserId'], '1');

					# manager is part of his/her own group
					$groups=array();
					$users=array();
					$groups[] = $groupId;
					$users[] = $val['typeUserId'];
					UserGroupInsertUser($groups, $users);
					
					# remember adding the group
					$this->list[$val['typeId']]['groupId'] = $groupId;
					$groupsList[] = $groupId;

				}
				# add user to group
				$groups=array();
				$users=array();
				$groups[] = $this->list[$val['typeId']]['groupId'];
				$users[] = $val['userId'];
				UserGroupInsertUser($groups, $users);

			}

		}

		# output
		$this->ses['response']['param']['groups'] = $groupsList;

    }

}

?>
