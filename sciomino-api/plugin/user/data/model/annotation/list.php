<?

class annotationList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$annotationProperties = array();
	$annotationList = array();
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
        $this->value = $this->ses['request']['param']['value'];
        $this->value_match = $this->ses['request']['param']['value_match'];
        if (! isset($this->value_match)) {$this->value_match = 'contains';}
 
        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	#
	# Contruct TABLE
	#
	# TODO: object and object_id are compulsory!
	$annotationProperties = GetAnnotationProperties($this->object);
	$table = $annotationProperties['table']; 
	$where = "WHERE {$annotationProperties['reference']} = $this->object_id";

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AnnotationId = \"".$this->id."\")";
        }

	# Search with searchwords in firstname an lastname
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AnnotationAttribute like \"%".safeInsert($this->query)."%\" OR AnnotationValue like \"%".safeInsert($this->query)."%\")";
        }
        if (isset($this->name)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('AnnotationAttribute', $this->name, $this->name_match);
        }
        if (isset($this->value)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('AnnotationValue', $this->value, $this->value_match);
        }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|name|value
		#
		switch ($this->order) {
			case "id":
               			$order .= "AnnotationId";
				break;
			case "name":
               			$order .= "AnnotationAttribute";
				$fix_for_same_entry = 1;
				break;
			case "value":
               			$order .= "AnnotationValue";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "AnnotationId";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", AnnotationId";
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
	$annotationList = UserAnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("AnnotationId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['annotationList'] = $annotationList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
