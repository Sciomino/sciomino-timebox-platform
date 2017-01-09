<?

class connectList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$connectList = array();
	$table = "";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 0;

	#
	# get params
	#
	$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
        $this->query = $this->ses['request']['param']['query'];
        
	$this->type = $this->ses['request']['param']['type'];
        $this->name = $this->ses['request']['param']['name'];
        $this->name_match = $this->ses['request']['param']['name_match'];
        if (! isset($this->name_match)) {$this->name_match = 'contains';}
 
        $this->reference = $this->ses['request']['param']['reference'];
        $this->reference_match = $this->ses['request']['param']['reference_match'];
        if (! isset($this->reference_match)) {$this->reference_match = 'exact';}

        $this->annotation = $this->ses['request']['param']['annotation'];
        $this->annotation_param = $this->ses['request']['param']['annotation_param'];
        if (! isset($this->annotation_param)) {$this->annotation_param = 'all';}

        $this->profile = $this->ses['request']['param']['profile'];
        $this->profile_param = $this->ses['request']['param']['profile_param'];
        if (! isset($this->profile_param)) {$this->profile_param = 'all';}

        $this->format = $this->ses['request']['param']['format'];
        if (! isset($this->format)) {$this->format = 'normal';}

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	if ($this->ses['request']['param']['format'] == "long") {$expand = 1;}

	#
	# Check type!
	#
	$mode = "off";
	if (in_array($this->type, array_keys($XCOW_B['connect_api']['connection']))) {
		$mode = $XCOW_B['connect_api']['connection'][$this->type]['location'];
	}

	if ($mode == "internal") {
		#
		# Contruct TABLE
		#
		$table = "Connection"; 
		$where = "";

		#
		# Contruct WHERE
		#
		if (isset($this->id)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "(Connection.ConnectionId = \"".$this->id."\")";
		}

		# Search with searchwords in name
		if (isset($this->query)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "(ConnectionName like \"%".$this->query."%\" OR ConnectionName like \"%".$this->query."%\")";
		}
		if (isset($this->name)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= constructWhereWithMatch('ConnectionName', $this->name, $this->name_match);
		}

		# Search for a type & reference
		if (isset($this->type)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "(Connection.ConnectionType = \"".$this->type."\")";
		}
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

			foreach (array_keys($this->annotation) as $aKey) {
				if ($this->annotation[$aKey]) {
					$annotations[] = "(AnnotationAttribute = \"".$aKey."\" AND AnnotationValue = \"".$this->annotation[$aKey]."\")";
				}
				else {
					$annotations[] = "AnnotationAttribute = \"".$aKey."\"";
				}
			}

			$annotationString .= constructMultipleWhereWithParam('', $annotations, $this->annotation_param, 'numberlist');

		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "Connection.ConnectionId IN (SELECT ConnectAnnotation.ConnectionId FROM ConnectAnnotation WHERE $annotationString)";
		}

		#
		# Search with array of profiles
		#
		# profile[kennisvelden]
		# profile[kennisvelden] = programmeren
		#
		# profiles have also annotations, like 'skill=5', these cannot be searched on this level
		#
		if (isset($this->profile)) {
			$profileString = "";
			$profiles = array();

			foreach (array_keys($this->profile) as $aKey) {
				if ($this->profile[$aKey]) {
					$profiles[] = "(ProfileGroup = \"".$aKey."\" AND ProfileName = \"".$this->profile[$aKey]."\")";
				}
				else {
					$profiles[] = "ProfileGroup = \"".$aKey."\"";
				}
			}

			$profileString .= constructMultipleWhereWithParam('', $profiles, $this->profile_param, 'numberlist');

		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "Connection.ConnectionId IN (SELECT ConnectionProfile.ConnectionId FROM ConnectionProfile WHERE $profileString)";
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
				if (GetAnnotationTypeFromAnnotationAttribute('connect', $attribute[1]) == 'int') {
		        		$order .= "(SELECT (AnnotationValue+0) FROM ConnectionAnnotation WHERE AnnotationAttribute = '$attribute[1]' AND ConnectionAnnotation.ConnectionId = Connection.ConnectionId)";
				}
				else {
		        		$order .= "(SELECT AnnotationValue FROM ConnectionAnnotation WHERE AnnotationAttribute = '$attribute[1]' AND ConnectionAnnotation.ConnectionId = Connection.ConnectionId)";
				}
			}
			else {

				#
				# no annotation, switch other options
				# - options: id|name|DATE|annotation/ANNOTATION-ATTRIBUTE
				#
				switch ($this->order) {
					case "id":
			       			$order .= "Connection.ConnectionId";
						break;
					case "name":
			       			$order .= "ConnectionName";
						break;
					case "date":
			       			$order .= "ConnectionTimestamp";
						break;
					default:
						$order .= "ConnectionTimestamp";
						$fix_for_same_entry = 1;
				}
			}

			if (isset($this->direction)) {
		        	$order .= " ";
		        	$order .= "$this->direction";
			}

			if ($fix_for_same_entry) {
		       		$order .= ", Connection.ConnectionId";
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
		$connectList = ConnectListWithValues($table, $where, $order, $limit, $expand);

		#
		# Summary
		#
		$this->ses['response']['param']['listSize'] = MysqlCountWithValues("Connection.ConnectionId", $table, $where, $order);

		if ($this->offset) {
			$this->ses['response']['param']['listCursor'] = $this->offset;
		}
		else {
			$this->ses['response']['param']['listCursor'] = 0;
		}

		#
		# View internal, connectListLong:
		# - all fields (normal view: name only)
		#
		if ($this->format == "long") {
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/api/connect/connect/listLong.php';
		}

	}
	elseif ($mode == "external") {
		# suggest list
		if (isset($this->query)) {

			$connectList = getConnectorSuggestList($this->type, $this->query);

		}
		# view item
		elseif (isset($this->name)) {

			$connectList = getConnectorViewList($this->type, $this->name);

			#
			# View external, connectListExternal:
			# - name
			# - url
			# - description
			#
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/api/connect/connect/listExternal.php';
		}
	}
	else {
		# error 400
	}

	#
	# Content
	#
	$this->ses['response']['param']['connectList'] = $connectList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
