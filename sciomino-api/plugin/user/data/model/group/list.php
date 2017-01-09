<?

class groupList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$groupList = array();
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
        
 	$this->name = $this->ses['request']['param']['name'];
        $this->name_match = $this->ses['request']['param']['name_match'];
        if (! isset($this->name_match)) {$this->name_match = 'contains';}
	$this->description = $this->ses['request']['param']['description'];
        $this->description_match = $this->ses['request']['param']['description_match'];
        if (! isset($this->description_match)) {$this->description_match = 'contains';}
	$this->type = $this->ses['request']['param']['type'];

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	if ($this->ses['request']['param']['format'] == "short") {$expand = 0;}

	#
	# Contruct TABLE
	#
	$table = "UserGroup"; 
	$where = "";
        if (isset($this->userId) && $this->userId != '') {
		$where = "WHERE UserGroup.UserId=".$this->userId;
	}	

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserGroup.UserGroupId = \"".$this->id."\")";
        }

	# Search with searchwords in account
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserGroupName like \"%".safeInsert($this->query)."%\" OR UserGroupDescription like \"%".safeInsert($this->query)."%\")";
        }

        if (isset($this->name)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('UserGroupName', $this->name, $this->name_match);
        }
        if (isset($this->description)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('UserGroupDescription', $this->description, $this->description_match);
        }

        if (isset($this->type)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserGroup.UserGroupType = \"".safeInsert($this->type)."\")";
        }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|name|description|type|DATE
		#
		switch ($this->order) {
			case "id":
               			$order .= "UserGroup.UserGroupId";
				break;
			case "name":
               			$order .= "UserGroupName";
				$fix_for_same_entry = 1;
				break;
			case "description":
               			$order .= "UserGroupDescription";
				$fix_for_same_entry = 1;
				break;
			case "type":
               			$order .= "UserGroupType";
				$fix_for_same_entry = 1;
				break;
			case "date":
               			$order .= "UserGroupTimestamp";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "UserGroupTimestamp";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", UserGroup.UserGroupId";
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
	$groupList = UserGroupListWithValues($table, $where, $order, $limit, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("UserGroup.UserGroupId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['groupList'] = $groupList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
