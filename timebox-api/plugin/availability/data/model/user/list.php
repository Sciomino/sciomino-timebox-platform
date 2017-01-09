<?

class userList extends control {

    function Run() {

        global $XCOW_B;

		#
		# init
		#
		$userList = array();
		$table = "";
		$where = "";
		$order = "";
		$limit = "";
		$expand = 0;

		#
		# get params
		#
		# !!!!! note: id = UserLoginName !!!!!
		#
		$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
		$this->idList = $this->ses['request']['param']['user'];

		# - required group parameter
        $this->group = $this->ses['request']['param']['group'];        

		# - also allow search on firstname/lastname :-)
        $this->query = $this->ses['request']['param']['query'];        
		$this->firstName = $this->ses['request']['param']['firstName'];
        $this->firstName_match = $this->ses['request']['param']['firstName_match'];
        if (! isset($this->firstName_match)) {$this->firstName_match = 'contains';}
        $this->lastName = $this->ses['request']['param']['lastName'];
        $this->lastName_match = $this->ses['request']['param']['lastName_match'];
        if (! isset($this->lastName_match)) {$this->lastName_match = 'contains';}

		# - dates
		$this->from = $this->ses['request']['param']['from'];
        $this->to = $this->ses['request']['param']['to'];
 
		# - keep this accessId parameter for a while, might be handy...
        $this->accessId = $this->ses['request']['param']['accessId'];
        $this->accessId_match = $this->ses['request']['param']['accessId_match'];
        if (! isset($this->accessId_match)) {$this->accessId_match = 'exact';}

        $this->order = $this->ses['request']['param']['order'];
        if (! isset($this->order)) {$this->order = 'loginname';}
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

		if ($this->ses['request']['param']['format'] == "short") {$expand = 0;}
		if ($this->ses['request']['param']['format'] == "long") {$expand = 1;}
		
		$this->view = $this->ses['request']['param']['view'];

		# mode
		# - all: toon alle resultaten
		# - active: de geactiveerde accounts worden getoond
		#   (op basis van aanwezigheid van een voornaam)
        $this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = "all";}

		#
		# go
		#
		if ($this->ses['response']['param']['appId'] != 0) {
				
			#
			# Contruct TABLE
			#
			$table = "User, UserInGroup, UserGroup"; 
			$where = "WHERE User.UserId = UserInGroup.UserId AND UserInGroup.UserGroupId = UserGroup.UserGroupId AND UserGroup.UserGroupType = 'availability-customer' AND UserGroup.UserGroupName = '".safeInsert($this->group)."'";

			#
			# Contruct WHERE
			#
			if ($this->mode == "active") {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= "(User.UserFirstName != \"\")";
			}
	 
			if (isset($this->id)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= "(User.UserLoginName = \"".$this->id."\")";
			}
			if (isset ($this->idList)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$userString = implode(",",array_keys($this->idList));
					$where .= "(User.UserLoginName in ($userString))";
			}

			# Search with searchwords in firstname an lastname
			if (isset($this->query)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					#$where .= "(UserFirstName like \"%".$this->query."%\" OR UserLastName like \"%".$this->query."%\")";
					$where .= "(CONCAT_WS(' ', UserFirstName, UserLastName) like \"%".safeInsert($this->query)."%\")";
			}
			if (isset($this->firstName)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= constructWhereWithMatch('UserFirstName', $this->firstName, $this->firstName_match);
			}
			if (isset($this->lastName)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= constructWhereWithMatch('UserLastName', $this->lastName, $this->lastName_match);
			}

			# Search with dates
			if (isset($this->from)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= "( User.UserId IN (SELECT UserProfile.UserId FROM UserProfile WHERE (ProfileGroup = 'availability' AND UserProfile.ProfileId in (SELECT DISTINCT UserProfile.ProfileId FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = 'timestamp' AND UserProfileAnnotation.AnnotationValue >= '".safeInsert($this->from)."'))) ) )";
			}
			if (isset($this->to)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= "( User.UserId IN (SELECT UserProfile.UserId FROM UserProfile WHERE (ProfileGroup = 'availability' AND UserProfile.ProfileId in (SELECT DISTINCT UserProfile.ProfileId FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = 'timestamp' AND UserProfileAnnotation.AnnotationValue <= '".safeInsert($this->to)."'))) ) )";
			}

			# Search for visible users only
			if (isset($this->accessId)) {
					if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
					$where .= constructWhereWithMatch('AccessRuleId', $this->accessId, $this->accessId_match);
			}

			#
			# Contruct ORDER
			#
			if (isset($this->order)) {
				$fix_for_same_entry = 0;
				$order .= "ORDER BY ";

				#
				# check for annotation
				#
				if (strstr($this->order, "annotation/")) {
					$attribute = explode ("/", $this->order, 2);
					# LET OP: sorteer op nummers ipv. op strings indien nodig!
					if (GetAnnotationTypeFromAnnotationAttribute('user', $attribute[1]) == 'int') {
								$order .= "(SELECT (AnnotationValue+0) FROM UserAnnotation WHERE AnnotationAttribute = '$attribute[1]' AND UserAnnotation.UserId = User.UserId)";
					}
					else {
								$order .= "(SELECT AnnotationValue FROM UserAnnotation WHERE AnnotationAttribute = '$attribute[1]' AND UserAnnotation.UserId = User.UserId)";
					}
				}
				else {

					#
					# no annotation, switch other options
					# - options: id|firstname|lastname|DATE|annotation/ANNOTATION-ATTRIBUTE
					#
					switch ($this->order) {
						case "id":
								$order .= "User.UserId";
							break;
						case "loginname":
								$order .= "User.UserLoginName";
							break;
						case "firstname":
								$order .= "UserFirstName";
							break;
						case "lastname":
								$order .= "UserLastName";
							break;
						case "date":
								$order .= "UserTimestamp";
							break;
							break;
						default:
							$order .= "UserTimestamp";
							$fix_for_same_entry = 1;
					}
				}

				if (isset($this->direction)) {
						$order .= " ";
						$order .= "$this->direction";
				}

				if ($fix_for_same_entry) {
						$order .= ", User.UserId";
						if (isset($this->direction)) {
								$order .= " ";
								$order .= "$this->direction";
						}
				}
			}
	
			#
			# Contruct LIMIT
			#
			if (isset($this->limit)) {
				$limit .= "LIMIT ";
				$limit .= "$this->limit";

				if (isset($this->offset)) {
						$limit .= " OFFSET ";
						$limit .= "$this->offset";
				}
			}

			#
			# Get List
			#
			$userList = UserListWithValues($table, $where, $order, $limit, $expand);

			#
			# Summary
			#
			$this->ses['response']['param']['listSize'] = MysqlCountWithValues("User.UserId", $table, $where, $order);
		}
		else {
			$this->ses['response']['param']['listSize'] = 0;
		}

		if ($this->offset) {
				$this->ses['response']['param']['listCursor'] = $this->offset;
		}
		else {
				$this->ses['response']['param']['listCursor'] = 0;
		}

		#
		# Content
		#
		$this->ses['response']['param']['userList'] = $userList;
		$this->ses['response']['param']['pageSize'] = count($userList);

		#
		# View
		#
		$this->ses['response']['param']['view'] = "xml";
		if ($this->view == "json") {
			$this->ses['response']['view'] = $XCOW_B['view_base'].'/api/availability/user/list.json';
			$this->ses['response']['param']['view'] = "json";
		}

    }

    function GetHeader() {

		if ($this->ses['response']['param']['view'] == "xml") {
			$this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
		}
		if ($this->ses['response']['param']['view'] == "json") {
			$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
		}
        return ($this->ses['response']['header']);

    }

}

?>
