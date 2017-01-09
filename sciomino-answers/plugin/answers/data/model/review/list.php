<?

class reviewList extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$reviewList = array();
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
  	$this->idList = $this->ses['request']['param']['review'];
        $this->query = $this->ses['request']['param']['query'];
        
	$this->score = $this->ses['request']['param']['score'];

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
	$table = "ActReview"; 
	$where = "";

	#
	# Contruct WHERE
	#
        if (isset($this->id)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActReview.ActReviewId = \"".$this->id."\")";
        }
        if (isset ($this->idList)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
 		$actString = implode(",",array_keys($this->idList));
                $where .= "(ActReview.ActReviewId in ($actString))";
        }

	# Search with searchwords in score
        if (isset($this->query)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActReviewScore =".$this->query."%\")";
        }
        if (isset($this->description)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActReviewScore =".$this->score."%\")";
        }

	# act
        if (isset($this->act)) {
                if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
                $where .= "(ActReview.ActId =".$this->act.")";
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
		# - options: id|score|act
		#
		switch ($this->order) {
			case "id":
	       			$order .= "ActReview.ActReviewId";
				break;
			case "score":
	       			$order .= "ActReviewScore";
				$fix_for_same_entry = 1;
				break;
			case "act":
	       			$order .= "ActReview.ActId";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "ActReview.ActReviewId";
				$fix_for_same_entry = 0;
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", ActReview.ActReviewId";
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
	$reviewList = ReviewListWithValues($table, $where, $order, $limit, $expand);

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("ActReview.ActReviewId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['reviewList'] = $reviewList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
