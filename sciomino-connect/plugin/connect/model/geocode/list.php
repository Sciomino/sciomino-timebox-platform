<?

class geoCodeList extends control {


    function Run() {

        global $XCOW_B;

		#
		# init
		#
		$geoCodeList = array();
		$table = "";
		$where = "";
		$order = "";
		$limit = "";

		#
		# get params
		#
		$this->id = $this->ses['request']['REST']['param'];
        #$this->id = $this->ses['request']['param']['id'];
        $this->query = $this->ses['request']['param']['query'];
        
		$this->cc = $this->ses['request']['param']['cc'];
		$this->ca = $this->ses['request']['param']['ca'];
        $this->name = $this->ses['request']['param']['name'];
        $this->name_match = $this->ses['request']['param']['name_match'];
        if (! isset($this->name_match)) {$this->name_match = 'exact';}

        $this->nameList = $this->ses['request']['param']['n'];
        if (! isset($this->nameList_param)) {$this->nameList_param = 'any';}

        $this->format = $this->ses['request']['param']['format'];
        if (! isset($this->format)) {$this->format = 'json';}

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 100;}

		#
		# Contruct TABLE
		#
		$table = "GEOcities"; 
		$where = "";

		#
		# Contruct WHERE
		#
		if (isset($this->id)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "(GEOcities.GEOcitiesId = \"".$this->id."\")";
		}

		# Search with searchwords in name
		if (isset($this->query)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "(GEOcitiesName like \"%".$this->query."%\")";
		}
		if (isset($this->name)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= constructWhereWithMatch('GEOcitiesName', $this->name, $this->name_match);
		}

		# Search with cc / ca
		if (isset($this->cc)) {
				if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
				$where .= "(GEOcities.GEOcitiesCC = \"".$this->cc."\")";
		}
		if (isset($this->ca)) {
		        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		        $where .= "(GEOcities.GEOcitiesCA = \"".$this->ca."\")";
		}

		#
		# Search with array of names
		#
		# n[city]
		# n[city,cc]
		# n[city,cc,ca]
		#
        #if (isset ($this->nameList)) {
        #        if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		#		$nameString = implode(",",array_keys($this->nameList));
        #        $where .= "(GEOcitiesName in ($nameString))";
        #}

        if (isset($this->nameList)) {
			$nameString = "";
			$names = array();
			$countNames = 0;
			foreach (array_keys($this->nameList) as $aKey) {
				// which where clause?
				if ($countNames == 0) {
					if ($where != "") { $where .= " AND ("; } else { $where .= "WHERE ("; }
					$countNames++;
				}
				else {
					if ($this->nameList_param == "all") { $where .= " AND "; }
					if ($this->nameList_param == "any") { $where .= " OR "; }
				}

				list($ci, $cc, $ca) = explode(",", $aKey);
				if ($ca != "") {
					# $nameString = "(GEOcities.GEOcitiesId in (SELECT DISTINCT GEOcities.GEOcitiesId FROM GEOcities WHERE (GEOcitiesName = \"".safeInsert($aKey)."\" AND GEOcitiesCC = \"".safeInsert($this->nameList[$aKey])."\")))";
					$nameString = "(GEOcitiesName = \"".safeInsert($ci)."\" AND GEOcitiesCC = \"".safeInsert($cc)."\" AND GEOcitiesCA = \"".safeInsert($ca)."\")";
				}
				elseif ($cc != "") {
					# $nameString = "(GEOcities.GEOcitiesId in (SELECT DISTINCT GEOcities.GEOcitiesId FROM GEOcities WHERE (GEOcitiesName = \"".safeInsert($aKey)."\" AND GEOcitiesCC = \"".safeInsert($this->nameList[$aKey])."\")))";
					$nameString = "(GEOcitiesName = \"".safeInsert($ci)."\" AND GEOcitiesCC = \"".safeInsert($cc)."\")";
				}
				else {
					# $nameString = "(GEOcities.GEOcitiesId in (SELECT DISTINCT GEOcities.GEOcitiesId FROM GEOcities WHERE GEOcitiesName = \"".safeInsert($aKey)."\"))";
					$nameString = "(GEOcitiesName = \"".safeInsert($aKey)."\")";
				}
				$where .= "$nameString";
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
			# no annotation, switch other options
			# - options: id|name|cc
			#
			switch ($this->order) {
				case "id":
					$order .= "GEOcities.GEOcitiesId";
					break;
				case "name":
					$order .= "GEOcitiesName";
					$fix_for_same_entry = 1;
					break;
				case "cc":
					$order .= "GEOcitiesCC";
					$fix_for_same_entry = 1;
					break;
				default:
					$order .= "GEOcities.GEOcitiesId";
			}

			if (isset($this->direction)) {
		        	$order .= " ";
		        	$order .= "$this->direction";
			}

			if ($fix_for_same_entry) {
		       		$order .= ", GEOcities.GEOcitiesId";
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
		$geoCodeList = ConnectGeoCodeListWithValues($table, $where, $order, $limit);

		# be smart!?
		# - first, the primary name on top
		# - second, add alternatives when they are not the same location
		# - TODO: can you be smart when multiple primary names match?
		#   example: Hengelo (overijssel + gelderland)
		#   solution: sorteer op aantal inwoners
		$smartGeoCodeList = array();
		$alternativeList = array();
		$geoSeen = array();
		foreach ($geoCodeList as $key => $val) {
			if ($val['primary'] == 1) {
				$smartGeoCodeList[] = $val;
				$geoSeen[$val['lat'].$val['lon']] = 1;
			}
			else {
				$alternativeList[] = $val;
			}
		}
		foreach ($alternativeList as $key => $val) {
			if (! array_key_exists($val['lat'].$val['lon'],$geoSeen)) {
				$smartGeoCodeList[] = $val;
				$geoSeen[$val['lat'].$val['lon']] = 1;
			}
		}

		#
		# Summary
		#
		$this->ses['response']['param']['listSize'] = MysqlCountWithValues("GEOcities.GEOcitiesId", $table, $where, $order);

		if ($this->offset) {
			$this->ses['response']['param']['listCursor'] = $this->offset;
		}
		else {
			$this->ses['response']['param']['listCursor'] = 0;
		}

		#
		# View default is json
		#
		#if ($this->format == "long") {
	    #  	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/api/connect/connect/listLong.php';
		#}


	#
	# Content
	#
	$this->ses['response']['param']['geoCodeList'] = $smartGeoCodeList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
