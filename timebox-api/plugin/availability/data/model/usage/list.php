<?

class usageList extends control {

    function Run() {

        global $XCOW_B;

		#
		# init
		#
		$usageList = array();
		$table = "";
		$where = "";
		$order = "";
		$limit = "";
		$expand = 1;

		#
		# get params
		#
		# - from control...
		$this->groupId = $this->ses['response']['param']['groupId'];
       
		$this->year = $this->ses['request']['param']['year'];
		$this->month = $this->ses['request']['param']['month'];
		$this->day = $this->ses['request']['param']['day'];

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}
		
		$this->view = $this->ses['request']['param']['view'];

		#
		# Contruct TABLE
		#
		$table = "AuthAppUsage"; 
		$where = "WHERE AuthAppUsage.AuthAppGroupId=".$this->groupId;

		#
		# Contruct WHERE
		#
        if (isset($this->year)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AuthAppUsage.AuthAppUsageYear = \"".$this->year."\")";
        }
        if (isset($this->month)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AuthAppUsage.AuthAppUsageMonth = \"".$this->month."\")";
        }
        if (isset($this->day)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(AuthAppUsage.AuthAppUsageDay = \"".$this->day."\")";
        }

		#
		# Contruct ORDER
		#
        if (isset($this->order)) {
			$fix_for_same_entry = 0;
			$order .= "ORDER BY ";

			#
			# options: date
			#
			switch ($this->order) {
				case "date":
					$order .= "AuthAppUsage.AuthAppUsageYear, AuthAppUsage.AuthAppUsageMonth, AuthAppUsage.AuthAppUsageDay";
					break;
				default:
					$order .= "AuthAppGroupId";
			}

			if (isset($this->direction)) {
					$order .= " ";
					$order .= "$this->direction";
			}

			if ($fix_for_same_entry) {
					$order .= ", AuthAppUsage.AuthAppGroupId";
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
		$usageList = AvailabilityUsageListWithValues($table, $where, $order, $limit, $expand);

		#
		# Summary
		#
		$this->ses['response']['param']['listSize'] = MysqlCountWithValues("AuthAppUsage.AuthAppGroupId", $table, $where, $order);

		if ($this->offset) {
				$this->ses['response']['param']['listCursor'] = $this->offset;
		}
		else {
				$this->ses['response']['param']['listCursor'] = 0;
		}

		#
		# Content
		#
		$this->ses['response']['param']['usageList'] = $usageList;

		#
		# View
		#
		$this->ses['response']['param']['view'] = "xml";
		if ($this->view == "json") {
			$this->ses['response']['view'] = $XCOW_B['view_base'].'/api/availability/usage/list.json';
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
