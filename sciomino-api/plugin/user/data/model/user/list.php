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
	$expand = 1;

	#
	# get params
	#
	$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
  	$this->idList = $this->ses['request']['param']['user'];
        $this->query = $this->ses['request']['param']['query'];
        
	$this->firstName = $this->ses['request']['param']['firstName'];
        $this->firstName_match = $this->ses['request']['param']['firstName_match'];
        if (! isset($this->firstName_match)) {$this->firstName_match = 'contains';}
        $this->lastName = $this->ses['request']['param']['lastName'];
        $this->lastName_match = $this->ses['request']['param']['lastName_match'];
        if (! isset($this->lastName_match)) {$this->lastName_match = 'contains';}
 
        $this->reference = $this->ses['request']['param']['reference'];
		$this->referenceList = $this->ses['request']['param']['refX'];
        $this->reference_match = $this->ses['request']['param']['reference_match'];
        if (! isset($this->reference_match)) {$this->reference_match = 'exact';}

        $this->accessId = $this->ses['request']['param']['accessId'];
        $this->accessId_match = $this->ses['request']['param']['accessId_match'];
        if (! isset($this->accessId_match)) {$this->accessId_match = 'exact';}

        $this->annotation = $this->ses['request']['param']['annotation'];
        $this->annotation_param = $this->ses['request']['param']['annotation_param'];
        if (! isset($this->annotation_param)) {$this->annotation_param = 'all';}
        $this->annotation_operator = $this->ses['request']['param']['annotation_operator'];
        if (! isset($this->annotation_operator)) {$this->annotation_operator = 'equal';}

        $this->profile = $this->ses['request']['param']['profile'];
        $this->profile_param = $this->ses['request']['param']['profile_param'];
        if (! isset($this->profile_param)) {$this->profile_param = 'all';}

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	if ($this->ses['request']['param']['format'] == "short") {$expand = 0;}
	if ($this->ses['request']['param']['format'] == "long") {$expand = 2;}

		# mode
		# - all: toon alle resultaten
		# - active: de geactiveerde accounts worden getoond
		#   (op basis van aanwezigheid van een voornaam)
        $this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = "all";}

	#
	# Contruct TABLE
	#
	$table = "User"; 
	$where = "";

	#
	# Contruct WHERE
	#
        if ($this->mode == "active") {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(User.UserFirstName != \"\")";
        }
 
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(User.UserId = \"".$this->id."\")";
        }
        if (isset ($this->idList)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
 		$userString = implode(",",array_keys($this->idList));
                $where .= "(User.UserId in ($userString))";
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

	# Search for a reference
        if (isset($this->reference)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('Reference', $this->reference, $this->reference_match);
        }
        if (isset ($this->referenceList)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
				$referenceString = implode(",",array_keys($this->referenceList));
                $where .= "(Reference in ($referenceString))";
        }

	# Search for visible users only
        if (isset($this->accessId)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('AccessRuleId', $this->accessId, $this->accessId_match);
        }

	#
	# Search with array of annotations
	#
	# annotation[woonplaats]
	# annotation[woonplaats] = landsmeer
	#
        if (isset($this->annotation)) {
		$annotationString = "";
		$annotations = array();
		$countAnnotation = 0;
		foreach (array_keys($this->annotation) as $aKey) {
			// which where clause?
			if ($countAnnotation == 0) {
				if ($where != "") { $where .= " AND ("; } else { $where .= "WHERE ("; }
				$countAnnotation++;
			}
			else {
				if ($this->annotation_param == "all") { $where .= " AND "; }
				if ($this->annotation_param == "any") { $where .= " OR "; }
			}

			if ($this->annotation[$aKey]) {
				if ($this->annotation_operator == "ge") {
					$annotationString = "(User.UserId in (SELECT DISTINCT UserAnnotation.UserId FROM UserAnnotation WHERE (AnnotationAttribute = \"".safeInsert($aKey)."\" AND AnnotationValue >= ".safeInsert($this->annotation[$aKey]).")))";
				}
				else {
					$annotationString = "(User.UserId in (SELECT DISTINCT UserAnnotation.UserId FROM UserAnnotation WHERE (AnnotationAttribute = \"".safeInsert($aKey)."\" AND AnnotationValue = \"".safeInsert($this->annotation[$aKey])."\")))";
				}
			}
			else {
				$annotationString = "(User.UserId in (SELECT DISTINCT UserAnnotation.UserId FROM UserAnnotation WHERE AnnotationAttribute = \"".safeInsert($aKey)."\"))";
			}

                	#$where .= "( User.UserId IN ( SELECT UserAnnotation.UserId FROM UserAnnotation WHERE $annotationString ) )";
                	$where .= "$annotationString";
		}
		$where .= ")";
        }

	#
	# Search with array of profiles
	#
	# profile[kennisvelden][field][programmeren]
	# profile[kennisvelden][skill][5]
	#
	#
        if (isset($this->profile)) {
		$profileString = "";
		$profiles = array();
		$countProfile = 0;
		foreach (array_keys($this->profile) as $aKey) {
			foreach (array_keys($this->profile[$aKey]) as $bKey) {
				foreach (array_keys($this->profile[$aKey][$bKey]) as $cKey) {
					// which where clause?
					if ($countProfile == 0) {
						if ($where != "") { $where .= " AND ("; } else { $where .= "WHERE ("; }
						$countProfile++;
					}
					else {
						if ($this->profile_param == "all") { $where .= " AND "; }
						if ($this->profile_param == "any") { $where .= " OR "; }
					}
					// add value
					$profileString = "(ProfileGroup = '".safeInsert($aKey)."' AND UserProfile.ProfileId in (SELECT DISTINCT UserProfile.ProfileId FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = '".safeInsert($bKey)."' AND UserProfileAnnotation.AnnotationValue = '".safeInsert($cKey)."')))";
				        $where .= "( User.UserId IN (SELECT UserProfile.UserId FROM UserProfile WHERE $profileString) )";
				}
			}
		}
		$where .= ")";
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
			# - options: id|firstname|lastname|DATE|dateofbirth|annotation/ANNOTATION-ATTRIBUTE
			#
			switch ($this->order) {
				case "id":
		       			$order .= "User.UserId";
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
				case "birthday":
		       			$order .= "(SELECT (AnnotationValue+0) FROM UserAnnotation WHERE AnnotationAttribute = 'dateofbirthmonth' AND UserAnnotation.UserId = User.UserId) ASC,(SELECT (AnnotationValue+0) FROM UserAnnotation WHERE AnnotationAttribute = 'dateofbirthday' AND UserAnnotation.UserId = User.UserId) ASC";
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

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
