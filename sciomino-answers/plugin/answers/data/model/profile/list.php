<?

class profileList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$profileProperties = array();
	$profileList = array();
	$annotationProperties = array();
	$table = "";
	$where = "";
	$order = "";
	$limit = "";
	$query = "";
	$expand = 1;

	#
	# get params
	#
	$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
        $this->query = $this->ses['request']['param']['query'];

        $this->object = $this->ses['request']['param']['object'];
        $this->object_id = $this->ses['request']['param']['object_id'];
        
	$this->name = $this->ses['request']['param']['name'];
        $this->name_match = $this->ses['request']['param']['name_match'];
        if (! isset($this->name_match)) {$this->name_match = 'contains';}
        $this->group = $this->ses['request']['param']['group'];
        $this->group_match = $this->ses['request']['param']['group_match'];
        if (! isset($this->group_match)) {$this->group_match = 'contains';}

        $this->annotation = $this->ses['request']['param']['annotation'];
        $this->annotation_param = $this->ses['request']['param']['annotation_param'];
        if (! isset($this->annotation_param)) {$this->annotation_param = 'all';}
 
        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	#
	# Contruct TABLE
	#
	# TODO: object and object_id are compulsory!
	$profileProperties = GetProfileProperties($this->object);
	$annotationProperties = GetAnnotationProperties($profileProperties['annotation']);
	$table = $profileProperties['table']; 
	$where = "WHERE {$profileProperties['reference']} = $this->object_id";

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ProfileId = \"".$this->id."\")";
        }

	# Search with searchwords in firstname an lastname
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ProfileName like \"%".safeInsert($this->query)."%\" OR ProfileGroup like \"%".safeInsert($this->query)."%\")";
        }
        if (isset($this->name)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('ProfileName', $this->name, $this->name_match);
        }
        if (isset($this->group)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('ProfileGroup', $this->group, $this->group_match);
        }

	#
	# Search with array of annotations
	#
        if (isset($this->annotation)) {
		$annotationString = "";
		$annotations = array();

		foreach (array_keys($this->annotation) as $aKey) {
			if ($this->annotation[$aKey]) {
				$annotations[] = "(AnnotationAttribute = \"".safeInsert($aKey)."\" AND AnnotationValue = \"".safeInsert($this->annotation[$aKey])."\")";
			}
			else {
				$annotations[] = "AnnotationAttribute = \""safeInsert(.$aKey)."\"";
			}
		}

		$annotationString .= constructMultipleWhereWithParam('', $annotations, $this->annotation_param, 'numberlist');

                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "ProfileId IN (SELECT {$annotationProperties['table']}.ProfileId FROM {$annotationProperties['table']} WHERE $annotationString)";
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
			if (GetAnnotationTypeFromAnnotationAttribute($profileProperties['annotation'], $attribute[1]) == 'int') {
                		$order .= "(SELECT (AnnotationValue+0) FROM {$annotationProperties['table']} WHERE AnnotationAttribute = '$attribute[1]' AND {$annotationProperties['table']}.ProfileId = {$profileProperties['table']}.ProfileId)";
			}
			else {
                		$order .= "(SELECT AnnotationValue FROM {$annotationProperties['table']} WHERE AnnotationAttribute = '$attribute[1]' AND {$annotationProperties['table']}.ProfileId = {$profileProperties['table']}.ProfileId)";
			}
		}

		else {
			#
			# options: id|name|group|annotation/ANNOTATION-ATTRIBUTE
			#
			switch ($this->order) {
				case "id":
		       			$order .= "ProfileId";
					break;
				case "name":
		       			$order .= "ProfileName";
					$fix_for_same_entry = 1;
					break;
				case "group":
		       			$order .= "ProfileGroup";
					$fix_for_same_entry = 1;
					break;
				default:
					$order .= "ProfileId";
			}
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", ProfileId";
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
	$profileList = ProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("ProfileId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['profileList'] = $profileList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
