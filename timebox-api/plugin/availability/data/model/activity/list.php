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
		# - from control...
		$this->appId = $this->ses['response']['param']['appId'];

		$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
        $this->query = $this->ses['request']['param']['query'];
        
		$this->title = $this->ses['request']['param']['title'];
        $this->title_match = $this->ses['request']['param']['title_match'];
        if (! isset($this->title_match)) {$this->title_match = 'contains';}
		$this->description = $this->ses['request']['param']['description'];
        $this->description_match = $this->ses['request']['param']['description_match'];
        if (! isset($this->description_match)) {$this->description_match = 'contains';}
		$this->priority = $this->ses['request']['param']['priority'];

        $this->order = $this->ses['request']['param']['order'];
        if (! isset($this->order)) {$this->order = id;}
		$this->direction = $this->ses['request']['param']['direction'];
        if (! isset($this->direction)) {$this->direction = desc;}
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}
		
		$this->view = $this->ses['request']['param']['view'];

		#
		# Contruct TABLE
		#
		$table = "AuthAppActivity"; 
		$where = "WHERE AuthAppActivity.AuthAppId=".$this->appId;

		#
		# Contruct WHERE
		#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AuthAppActivity.AuthAppActivityId = \"".$this->id."\")";
        }

		# Search with searchwords in account
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AuthAppActivityTitle like \"%".safeInsert($this->query)."%\" OR AuthAppActivityDescription like \"%".safeInsert($this->query)."%\")";
        }

        if (isset($this->title)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('AuthAppActivityTitle', $this->title, $this->title_match);
        }
        if (isset($this->description)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('AuthAppActivityDescription', $this->description, $this->description_match);
        }

        if (isset($this->priority)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AuthAppActivity.AuthAppActivityPriority = \"".$this->priority."\")";
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
							$order .= "AuthAppActivity.AuthAppActivityId";
					break;
				case "title":
							$order .= "AuthAppActivityTitle";
					$fix_for_same_entry = 1;
					break;
				case "description":
							$order .= "AuthAppActivityDescription";
					$fix_for_same_entry = 1;
					break;
				case "priority":
							$order .= "AuthAppActivityPriority";
					$fix_for_same_entry = 1;
					break;
				case "date":
					$order .= "AuthAppActivityTimestamp";
					$fix_for_same_entry = 1;
					break;
				default:
					$order .= "AuthAppActivityTimestamp";
			}

			if (isset($this->direction)) {
					$order .= " ";
					$order .= "$this->direction";
			}

			if ($fix_for_same_entry) {
					$order .= ", AuthAppActivity.AuthAppActivityId";
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
		$activityList = AvailabilityActivityListWithValues($table, $where, $order, $limit, $expand);

		#
		# Summary
		#
		$this->ses['response']['param']['listSize'] = MysqlCountWithValues("AuthAppActivity.AuthAppActivityId", $table, $where, $order);

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

		#
		# View
		#
		$this->ses['response']['param']['view'] = "xml";
		if ($this->view == "json") {
			$this->ses['response']['view'] = $XCOW_B['view_base'].'/api/availability/activity/list.json';
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
