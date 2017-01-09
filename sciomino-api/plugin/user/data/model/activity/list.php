<?

class activityList extends control {


    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$activityList = array();
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
        
 	$this->title = $this->ses['request']['param']['title'];
 	$this->titleList = $this->ses['request']['param']['tl'];
        $this->title_match = $this->ses['request']['param']['title_match'];
        if (! isset($this->title_match)) {$this->title_match = 'exact';}
	$this->description = $this->ses['request']['param']['description'];
        $this->description_match = $this->ses['request']['param']['description_match'];
        if (! isset($this->description_match)) {$this->description_match = 'contains';}
	$this->priority = $this->ses['request']['param']['priority'];

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	#
	# Contruct TABLE
	#
	$table = "UserActivity"; 
	$where = "";
        if (isset($this->userId) && $this->userId != '') {
		$where = "WHERE UserActivity.UserId=".$this->userId;
	}	

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserActivity.UserActivityId = \"".$this->id."\")";
        }

	# Search with searchwords in account
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserActivityTitle like \"%".safeInsert($this->query)."%\" OR UserActivityDescription like \"%".safeInsert($this->query)."%\")";
        }

        if (isset($this->title)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('UserActivityTitle', $this->title, $this->title_match);
        }
        if (isset ($this->titleList)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
				$titleString = "'" . implode("','", array_values($this->titleList)) . "'";
				if ($this->title_match == "not") {
					$where .= "(UserActivityTitle not in ($titleString))";
				}
				else {
					$where .= "(UserActivityTitle in ($titleString))";
				}
        }
        if (isset($this->description)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('UserActivityDescription', $this->description, $this->description_match);
        }

        if (isset($this->priority)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserActivity.UserActivityPriority = \"".$this->priority."\")";
        }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|title|description|priority|DATE
		#
		switch ($this->order) {
			case "id":
               			$order .= "UserActivity.UserActivityId";
				break;
			case "title":
               			$order .= "UserActivityTitle";
				$fix_for_same_entry = 1;
				break;
			case "description":
               			$order .= "UserActivityDescription";
				$fix_for_same_entry = 1;
				break;
			case "priority":
               			$order .= "UserActivityPriority";
				$fix_for_same_entry = 1;
				break;
			case "date":
               			$order .= "UserActivityTimestamp";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "UserActivityTimestamp";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", UserActivity.UserActivityId";
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
	$activityList = UserActivityListWithValues($table, $where, $order, $limit, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("UserActivity.UserActivityId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['activityList'] = $activityList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
