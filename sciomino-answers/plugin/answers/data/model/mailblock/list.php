<?

class mailblockList extends control {

    function Run() {

        global $XCOW_B;

		#
		# init
		#
		$mailblockList = array();
		$table = "";
		$where = "";
		$order = "";
		$limit = "";
		$expand = 1;

		#
		# get params
		#
		$this->id = $this->ses['request']['REST']['param'];
		#$this->id = $this->ses['request']['param']['id'];
		$this->idList = $this->ses['request']['param']['mailblock'];
        
        $this->act = $this->ses['request']['param']['act'];

        $this->reference = $this->ses['request']['param']['reference'];
        $this->reference_match = $this->ses['request']['param']['reference_match'];
        if (! isset($this->reference_match)) {$this->reference_match = 'exact';}

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

		if ($this->ses['request']['param']['format'] == "short") {$expand = 0;}

		#
		# Contruct TABLE
		#
		$table = "ActMailblock"; 
		$where = "";

		#
		# Contruct WHERE
		#
        if (isset($this->id)) {
			if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
			$where .= "(ActMailblock.ActMailblockId = \"".$this->id."\")";
        }
        if (isset ($this->idList)) {
			if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
			$actString = implode(",",array_keys($this->idList));
			$where .= "(ActMailblock.ActMailblockId in ($actString))";
        }

		# act
        if (isset($this->act)) {
			if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
			$where .= "(ActMailblock.ActId =".$this->act.")";
        }

		# Search for a reference
        if (isset($this->reference)) {
			if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
			$where .= constructWhereWithMatch('Reference', $this->reference, $this->reference_match);
        }

		#
		# Contruct ORDER
		#
        if (isset($this->order)) {
			$fix_for_same_entry = 0;
			$order .= "ORDER BY ";

			#
			# - options: id|act
			#
			switch ($this->order) {
				case "id":
					$order .= "ActMailblock.ActMailblockId";
					break;
				case "act":
					$order .= "ActMailblock.ActId";
					$fix_for_same_entry = 1;
					break;
				default:
					$order .= "ActMailblock.ActMailblockId";
					$fix_for_same_entry = 0;
			}

			if (isset($this->direction)) {
				$order .= " ";
				$order .= "$this->direction";
			}

			if ($fix_for_same_entry) {
				$order .= ", ActMailblock.ActMailblockId";
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
		$mailblockList = MailblockListWithValues($table, $where, $order, $limit, $expand);

		#
		# Summary
		#
		$this->ses['response']['param']['listSize'] = MysqlCountWithValues("ActMailblock.ActMailblockId", $table, $where, $order);

		if ($this->offset) {
			$this->ses['response']['param']['listCursor'] = $this->offset;
		}
		else {
			$this->ses['response']['param']['listCursor'] = 0;
		}

		#
		# Content
		#
		$this->ses['response']['param']['mailblockList'] = $mailblockList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
