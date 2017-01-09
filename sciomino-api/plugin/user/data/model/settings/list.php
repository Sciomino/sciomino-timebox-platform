<?

class settingsList extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$settingsList = array();
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

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

	#
	# Contruct TABLE
	#
	$table = "UserSettings"; 
	$where = "";
        if (isset($this->userId) && $this->userId != '') {
		$where = "WHERE UserSettings.UserId=".$this->userId;
	}	

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserSettings.UserSettingsId = \"".$this->id."\")";
        }

	# Search with searchwords in account
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(UserSettingsAttribute like \"%".safeInsert($this->query)."%\")";
        }

        if (isset($this->name)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= constructWhereWithMatch('UserSettingsAttribute', $this->name, $this->name_match);
        }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|name
		#
		switch ($this->order) {
			case "id":
               			$order .= "UserSettings.UserSettingsId";
				break;
			case "name":
               			$order .= "UserSettingsAttribute";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "UserSettings.UserSettingsId";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", UserSettings.UserSettingsId";
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
	$settingsList = UserSettingsListWithValues($table, $where, $order, $limit, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("UserSettings.UserSettingsId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['settingsList'] = $settingsList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
