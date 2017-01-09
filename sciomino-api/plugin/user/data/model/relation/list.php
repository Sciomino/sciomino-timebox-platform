<?

class relationList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$relationList = array();
	$table = "";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	#
	# get params
	#
	#$this->userId = makeIntString($this->ses['request']['param']['userId']);
	$this->userId = $this->ses['request']['param']['userId'];

	$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
        $this->query = $this->ses['request']['param']['query'];
        
	$this->relation = $this->ses['request']['param']['relation'];

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	#
	# Contruct TABLE
	#
	$table = "UserRelation"; 
	$where = "";
        if (isset($this->userId) && $this->userId != '') {
		$where = "WHERE UserRelation.UserId=".$this->userId;
	}	

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserRelation.UserRelationId = \"".$this->id."\")";
        }

	# Search with searchwords in account
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(1 = 1)";
        }

        if (isset($this->relation)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserRelation.UserRelationUserId = \"".$this->relation."\")";
        }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|relation
		#
		switch ($this->order) {
			case "id":
               			$order .= "UserRelation.UserRelationId";
				break;
			case "relation":
               			$order .= "UserRelationUserId";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "UserRelationTimestamp";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", UserRelation.UserRelationId";
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
	$relationList = UserRelationListWithValues($table, $where, $order, $limit, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("UserRelation.UserRelationId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['relationList'] = $relationList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
