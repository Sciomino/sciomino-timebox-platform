<?

class actList extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$actList = array();
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
  	$this->idList = $this->ses['request']['param']['act'];
        $this->query = $this->ses['request']['param']['query'];
        
	$this->description = $this->ses['request']['param']['description'];
        $this->description_match = $this->ses['request']['param']['description_match'];
        if (! isset($this->description_match)) {$this->description_match = 'contains';}
 
        $this->publish_from = $this->ses['request']['param']['publish_from'];
        $this->publish_to = $this->ses['request']['param']['publish_to'];
        $this->expire_from = $this->ses['request']['param']['expire_from'];
        $this->expire_to = $this->ses['request']['param']['expire_to'];
        $this->open = $this->ses['request']['param']['open'];
        $this->closed = $this->ses['request']['param']['closed'];
		
        $this->active = $this->ses['request']['param']['active'];
        if (! isset($this->active)) {$this->active = 1;}
        # parent:-1 > list reacts
        # parent:0  > list acts
        # parent:x  > list reacts of act x
        $this->parent = $this->ses['request']['param']['parent'];
        if (! isset($this->parent)) {$this->parent = 0;}

        $this->reference = $this->ses['request']['param']['reference'];
        $this->reference_match = $this->ses['request']['param']['reference_match'];
        if (! isset($this->reference_match)) {$this->reference_match = 'exact';}

        $this->annotation = $this->ses['request']['param']['annotation'];
        $this->annotation_param = $this->ses['request']['param']['annotation_param'];
        if (! isset($this->annotation_param)) {$this->annotation_param = 'all';}

        $this->profile = $this->ses['request']['param']['profile'];
        $this->profile_param = $this->ses['request']['param']['profile_param'];
        if (! isset($this->profile_param)) {$this->profile_param = 'all';}

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	if ($this->ses['request']['param']['format'] == "short") {$expand = 0;}

	#
	# Contruct TABLE
	#
	$table = "Act"; 
	$where = "";

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(Act.ActId = \"".$this->id."\")";
        }
        if (isset ($this->idList)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
 		$actString = implode(",",array_keys($this->idList));
                $where .= "(Act.ActId in ($actString))";
        }

	# Search with searchwords in description
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActDescription like \"%".safeInsert($this->query)."%\")";
        }
        if (isset($this->description)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('ActDescription', $this->description, $this->description_match);
        }

	# Search with dates
        if (isset($this->publish_from)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActTimestamp >=".$this->publish_from.")";
        }
        if (isset($this->publish_to)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActTimestamp <=".$this->publish_to.")";
        }
        if (isset($this->expire_from)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActExpiration >=".$this->expire_from.")";
        }
        if (isset($this->expire_to)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActExpiration <=".$this->expire_to.")";
        }
        if (isset($this->open)) {
				$currentTime = time();
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
 				$where .= "((ActTimestamp + ActExpiration) > ".$currentTime.")";
        }
        if (isset($this->closed)) {
				$currentTime = time();
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
 				$where .= "((ActTimestamp + ActExpiration) < ".$currentTime.")";
        }

	# active & parent
        if (isset($this->active)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActActive =".$this->active.")";
        }
        if (isset($this->parent)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                if ($this->parent == -1) {
					$where .= "(ActParent != 0)";
				}
				else {
					$where .= "(ActParent =".$this->parent.")";
				}
        }

	# Search for a reference
        if (isset($this->reference)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('Reference', $this->reference, $this->reference_match);
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
				$annotationString = "(Act.ActId in (SELECT DISTINCT ActAnnotation.ActId FROM ActAnnotation WHERE (AnnotationAttribute = \"".safeInsert($aKey)."\" AND AnnotationValue = \"".safeInsert($this->annotation[$aKey])."\")))";
			}
			else {
				$annotationString = "(Act.ActId in (SELECT DISTINCT ActAnnotation.ActId FROM ActAnnotation WHERE AnnotationAttribute = \"".safeInsert($aKey)."\"))";
			}

                	$where .= "( Act.ActId IN ( SELECT ActAnnotation.ActId FROM ActAnnotation WHERE $annotationString ) )";
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
					$profileString = "(ProfileGroup = '".safeInsert($aKey)."' AND ActProfile.ProfileId in (SELECT DISTINCT ActProfile.ProfileId FROM ActProfile, ActProfileAnnotation WHERE ActProfile.ProfileId = ActProfileAnnotation.ProfileId AND ActProfileAnnotation.ProfileId in (SELECT DISTINCT ActProfileAnnotation.ProfileId FROM ActProfileAnnotation WHERE ActProfileAnnotation.AnnotationAttribute = '".safeInsert($bKey)."' AND ActProfileAnnotation.AnnotationValue = '".safeInsert($cKey)."')))";
				        $where .= "( Act.ActId IN (SELECT ActProfile.ActId FROM ActProfile WHERE $profileString) )";
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
			if (GetAnnotationTypeFromAnnotationAttribute('act', $attribute[1]) == 'int') {
                		$order .= "(SELECT (AnnotationValue+0) FROM ActAnnotation WHERE AnnotationAttribute = '$attribute[1]' AND ActAnnotation.ActId = Act.ActId)";
			}
			else {
                		$order .= "(SELECT AnnotationValue FROM ActAnnotation WHERE AnnotationAttribute = '$attribute[1]' AND ActAnnotation.ActId = Act.ActId)";
			}
		}
		else {

			#
			# no annotation, switch other options
			# - options: id|description|DATE|EXPIRE DATE|annotation/ANNOTATION-ATTRIBUTE
			#
			switch ($this->order) {
				case "id":
		       			$order .= "Act.ActId";
					break;
				case "description":
		       			$order .= "ActDescription";
					$fix_for_same_entry = 1;
					break;
				case "date":
		       			$order .= "ActTimestamp";
					$fix_for_same_entry = 1;
					break;
				case "expire_date":
		       			$order .= "ActExpiration";
					$fix_for_same_entry = 1;
					break;
				default:
					$order .= "ActTimestamp";
					$fix_for_same_entry = 1;
			}
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", Act.ActId";
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
	$actList = ActListWithValues($table, $where, $order, $limit, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("Act.ActId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['actList'] = $actList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
