<?

class sectionList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$sectionProperties = array();
	$sectionList = array();
	$profileProperties = array();
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
        $this->type = $this->ses['request']['param']['type'];
        $this->type_match = $this->ses['request']['param']['type_match'];
        if (! isset($this->type_match)) {$this->type_match = 'contains';}

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
	# TODO: object and object_id are compulsory!
	$sectionProperties = GetSectionProperties($this->object);
	$annotationProperties = GetAnnotationProperties($sectionProperties['annotation']);
	$profileProperties = GetProfileProperties($sectionProperties['profile']);
	$profileAnnotationProperties = GetAnnotationProperties($profileProperties['annotation']);
	$table = $sectionProperties['table']; 
	$where = "WHERE {$sectionProperties['reference']} = $this->object_id";

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(SectionId = \"".$this->id."\")";
        }

	# Search with searchwords in firstname an lastname
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(SectionName like \"%".safeInsert($this->query)."%\" OR SectionType like \"%".safeInsert($this->query)."%\")";
        }
        if (isset($this->name)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('SectionName', $this->name, $this->name_match);
        }
        if (isset($this->type)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('SectionType', $this->type, $this->type_match);
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
				$annotationString = "(SectionId in (SELECT DISTINCT {$annotationProperties['table']}.SectionId FROM {$annotationProperties['table']} WHERE (AnnotationAttribute = \"".safeInsert($aKey)."\" AND AnnotationValue = \"".safeInsert($this->annotation[$aKey])."\")))";
			}
			else {
				$annotationString = "(SectionId in (SELECT DISTINCT {$annotationProperties['table']}.SectionId FROM {$annotationProperties['table']} WHERE AnnotationAttribute = \"".safeInsert($aKey)."\"))";
			}

                	$where .= "( SectionId IN ( SELECT {$annotationProperties['table']}.SectionId FROM {$annotationProperties['table']} WHERE $annotationString ) )";
		}
		$where .= ")";
        }

	#
	# Search with array of profiles
	#
	# profile[kennisvelden][field][programmeren]
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
					$profileString = "(ProfileGroup = '".safeInsert($aKey)."' AND {$profileProperties['table']}.ProfileId in (SELECT DISTINCT {$profileProperties['table']}.ProfileId FROM {$profileProperties['table']}, {$profileAnnotationProperties['table']} WHERE {$profileProperties['table']}.ProfileId = {$profileAnnotationProperties['table']}.ProfileId AND {$profileAnnotationProperties['table']}.ProfileId in (SELECT DISTINCT {$profileAnnotationProperties['table']}.ProfileId FROM {$profileAnnotationProperties['table']} WHERE {$profileAnnotationProperties['table']}.AnnotationAttribute = '".safeInsert($bKey)."' AND {$profileAnnotationProperties['table']}.AnnotationValue = '".safeInsert($cKey)."')))";
				        $where .= "( SectionId IN (SELECT {$profileProperties['table']}.SectionId FROM {$profileProperties['table']} WHERE $profileString) )";
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
			if (GetAnnotationTypeFromAnnotationAttribute($sectionProperties['annotation'], $attribute[1]) == 'int') {
                		$order .= "(SELECT (AnnotationValue+0) FROM {$annotationProperties['table']} WHERE AnnotationAttribute = '$attribute[1]' AND {$annotationProperties['table']}.SectionId = {$sectionProperties['table']}.SectionId)";
			}
			else {
                		$order .= "(SELECT AnnotationValue FROM {$annotationProperties['table']} WHERE AnnotationAttribute = '$attribute[1]' AND {$annotationProperties['table']}.SectionId = {$sectionProperties['table']}.SectionId)";
			}
		}

		else {
			#
			# options: id|name|type|annotation/ANNOTATION-ATTRIBUTE
			#
			switch ($this->order) {
				case "id":
		       			$order .= "SectionId";
					break;
				case "name":
		       			$order .= "SectionName";
					$fix_for_same_entry = 1;
					break;
				case "type":
		       			$order .= "SectionType";
					$fix_for_same_entry = 1;
					break;
				default:
					$order .= "SectionId";
			}
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", SectionId";
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
	$sectionList = UserSectionListWithValues($table, $where, $order, $limit, $sectionProperties, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("SectionId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['sectionList'] = $sectionList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
