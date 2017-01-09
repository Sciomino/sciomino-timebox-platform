<?

class indexList extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$indexList = array();

	# suggestions
	$suggest = 0;
	$suggestList = array();

	# knowledge
	$knowledgeList = array();

	# hobbies
	$hobbyList = array();

	# personal
	$businessunitList = array();
	$workplaceList = array();

	# status & my
	$statusList = array();
	$myList = array();

	# networks
	$networkList = array();

	$table = "";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	#
	# get params
	#
	$this->reference = $this->ses['request']['param']['reference'];

        $this->name = $this->ses['request']['param']['n'];
        $this->query = $this->ses['request']['param']['q'];
        
		$this->knowledgeList = $this->ses['request']['param']['k'];
		$this->hobbyList = $this->ses['request']['param']['h'];
		$this->personalList = $this->ses['request']['param']['p'];
		$this->tagList = $this->ses['request']['param']['t'];
		$this->statusList = $this->ses['request']['param']['s'];
		$this->myList = $this->ses['request']['param']['m'];

        $this->networkList = $this->ses['request']['param']['net'];

		$this->detail = $this->ses['request']['param']['detail'];
        if (! isset($this->detail)) {$this->detail = 'all';}
 
        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 500;}

        $this->subLimit = $this->ses['request']['param']['subLimit'];
        if (! isset($this->subLimit)) {$this->subLimit = 5;}
#$time1 = getMicrotime();

	#
	# Contruct TABLE
	#
	$table = "";
	$where = "";
	$join = "";

	#
	# Contruct WHERE
	#

	# search with name
        if (isset ($this->name)) {
		$suggest = 0;
		$words[] = $this->name;

	        if ($where != "") { $where .= " INNER JOIN ";}
		$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord LIKE '".safeInsert($this->name)."%' AND SearchWordContext = 'name') AS name";
	        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }

        }

	# Search with searchwords
        if (isset($this->query)) {
	 	$suggest = 1;
		# problem with like, with first % the index is not used!
                # $where .= "(SearchWord.SearchWordWord like \"%".$this->query."%\")";
                # $where .= "(SearchWord.SearchWordWord like \"".$this->query."%\")";

		$words = explode(" ", $this->query);
	
		$countWord = 1;
		foreach ($words as $word) {
	                if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord LIKE '".safeInsert($word)."%') AS word".$countWord;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countWord++;
		}

        }

	# search with knowledge
        if (isset ($this->knowledgeList)) {
		$suggest = 0;
		$countKnowledge = 1;
		foreach ($this->knowledgeList as $knowledge => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($knowledge)."' AND SearchWordContext = 'knowledge') AS knowledge".$countKnowledge;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countKnowledge++;
		}
        }

	# search with hobby
        if (isset ($this->hobbyList)) {
		$suggest = 0;
		$countHobby = 1;
		foreach ($this->hobbyList as $hobby => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($hobby)."' AND SearchWordContext = 'hobby') AS hobby".$countHobby;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countHobby++;
		}
        }

	# search with personal
        if (isset ($this->personalList)) {
		$suggest = 0;
		$countPersonal = 1;
		foreach ($this->personalList as $attribute => $value) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($value)."' AND SearchWordContext = '".safeInsert($attribute)."') AS personal".$countPersonal;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countPersonal++;
		}
        }

	# search with tag
        if (isset ($this->tagList)) {
		$suggest = 0;
		$countTag = 1;
		foreach ($this->tagList as $tag => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($tag)."' AND SearchWordContext = 'tag') AS tag".$countTag;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countTag++;
		}
        }

	# search with status
	# - all
	# - relevant (=open&closed+story)
	# - open
	# - open + time (example: close today, last week, last month)
	# - closed
	# - closed + story
	# - TODO: closed + no story
        if (isset ($this->statusList)) {
		$suggest = 0;
		$countStatus = 1;
		foreach ($this->statusList as $status => $sub) {
		        if ($where != "") { $where .= " INNER JOIN "; } else { $where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex) AS dummy INNER JOIN "; }
			$currentTime = time();
			if ($status == "open_day") { $status = "open"; $sub = 60*60*24; }
			if ($status == "open_week") { $status = "open"; $sub = 60*60*24*7; }
			if ($status == "open_month") { $status = "open"; $sub = 60*60*24*30; }
			if ($status == "open") {
				# sub contains seconds
				if (isset($sub) && $sub != "") {
					$endTime = $currentTime + $sub;
					$where .= "(SELECT DISTINCT ActId FROM Act WHERE (ActTimestamp + ActExpiration) > $currentTime AND (ActTimestamp + ActExpiration) < $endTime AND ActParent = 0) AS status".$countStatus;
				}
				else {
					$where .= "(SELECT DISTINCT ActId FROM Act WHERE (ActTimestamp + ActExpiration) > $currentTime AND ActParent = 0) AS status".$countStatus;
				}
			}
			elseif ($status == "closed") {
				$where .= "(SELECT DISTINCT ActId FROM Act WHERE (ActTimestamp + ActExpiration) < $currentTime AND ActParent = 0) AS status".$countStatus;
			}
			elseif ($status == "closed_story") {
				$where .= "(SELECT DISTINCT Act.ActId FROM Act, ActAnnotation WHERE (ActTimestamp + ActExpiration) < $currentTime AND ActParent = 0 AND Act.ActId IN (SELECT Act.ActParent from Act, ActAnnotation where Act.ActId= ActAnnotation.ActId AND AnnotationAttribute = 'story' AND AnnotationValue = '1')) AS status".$countStatus;
			}
			elseif ($status == "relevant") {
				# relevant = open OR closed + story
				$where .= "(SELECT DISTINCT Act.ActId FROM Act WHERE ((ActTimestamp + ActExpiration) > $currentTime AND ActParent = 0) OR ((ActTimestamp + ActExpiration) < $currentTime AND ActParent = 0 AND Act.ActId IN (SELECT Act.ActParent from Act, ActAnnotation where Act.ActId= ActAnnotation.ActId AND AnnotationAttribute = 'story' AND AnnotationValue = '1')) ) AS status".$countStatus;
			}
			elseif ($status == "all") {
				$where .= "(SELECT DISTINCT ActId FROM Act WHERE ActParent = 0) AS status".$countStatus;
			}

		        if ($join == "") { $join = "ReferenceId"; $where .= " ON ".$join." = status".$countStatus.".ActId"; } else { $where .= " ON ".$join." = status".$countStatus.".ActId"; }
			$countMy++;
		}
        }

	# search with my
	# - myAct
	# - myReact
        if (isset ($this->myList)) {
		$suggest = 0;
		$countMy = 1;
		foreach ($this->myList as $my => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; } else { $where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex) AS dummy INNER JOIN "; }
			if ($my == "act") {
				$where .= "(SELECT DISTINCT ActId FROM Act WHERE Reference = $this->reference AND ActParent = 0) AS my".$countMy;
		       		if ($join == "") { $join = "ReferenceId"; $where .= " ON ".$join." = my".$countMy.".ActId"; } else { $where .= " ON ".$join." = my".$countMy.".ActId"; }
			}
			elseif ($my == "react") {
				$where .= "(SELECT DISTINCT ActParent FROM Act WHERE Reference = $this->reference AND ActParent != 0) AS my".$countMy;
		       		if ($join == "") { $join = "ReferenceId"; $where .= " ON ".$join." = my".$countMy.".ActParent"; } else { $where .= " ON ".$join." = my".$countMy.".ActParent"; }
			}

			$countMy++;
		}
        }

		# search with network
        if (isset ($this->networkList)) {
			$suggest = 0;
			$countNetwork = 1;
			foreach ($this->networkList as $network => $dummy) {
				if ($where != "") { $where .= " INNER JOIN "; }
				$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($network)."' AND SearchWordContext = 'network') AS network".$countNetwork;
				if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
				$countNetwork++;
			}
		}
 
	# default find all!
	if ($where == "") { $where = "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex) AS dummy"; }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|time
		#
		switch ($this->order) {
			case "id":
               			$order .= "ReferenceId";
				break;
			case "time":
               			$order .= "(SELECT ActTimestamp FROM Act WHERE ReferenceId = Act.ActId)";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "ReferenceId";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", ReferenceId";
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
	$indexList = SearchIndexListWithValues($table, $where, $order, $limit, $expand);
	$indexString = implode(",",$indexList);

#$time2 = getMicrotime();

	#
	# suggestions
	#
	if ($suggest) {
		$suggestList = SearchWordGetContext($words);
		$suggestList = array_slice($suggestList, 0, $this->subLimit, true);
	}

	#
	# get knowledgefields AND experiences
	# - OR get knowledge details 
	# - OR get experience details
	#	

	# knowledgefields
	// remove the query keys from the list
	$knowledgeKeys = array();
	//if (isset ($this->knowledgeList)) {
	//	$knowledgeKeys = array_keys($this->knowledgeList);
	//}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "knowledgeOnly") ) {
		$profileList = ProfileListWithValues('ActProfile', 'WHERE ActId in ('.$indexString.') AND ProfileGroup = "knowledgefield"', '', '', GetProfileProperties('act'), 1);
		foreach ($profileList as $profile) {
			foreach ($profile['annotation'] as $annotation) {
				if (trim($annotation['value']) != "" && $annotation['name'] == 'field' && ! in_array($annotation['value'], $knowledgeKeys) ) {
					if (array_key_exists($annotation['value'], $knowledgeList)) {
						$knowledgeList[$annotation['value']] = $knowledgeList[$annotation['value']] + 1;
					}
					else {
						$knowledgeList[$annotation['value']] = 1;
					}
				}
			}
		}
		arsort($knowledgeList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$knowledgeList = array_slice($knowledgeList, 0, $this->subLimit, true);
		}
	}

	# hobbies
	// remove the query keys from the list
	$hobbyKeys = array();
	//if (isset ($this->hobbyList)) {
	//	$hobbyKeys = array_keys($this->hobbyList);
	//}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "hobbyOnly") ) {
		$profileList = ProfileListWithValues('ActProfile', 'WHERE ActId in ('.$indexString.') AND ProfileGroup = "hobbyfield"', '', '', GetProfileProperties('act'), 1);
		foreach ($profileList as $profile) {
			foreach ($profile['annotation'] as $annotation) {
				if (trim($annotation['value']) != "" && $annotation['name'] == 'field' && ! in_array($annotation['value'], $hobbyKeys) ) {
					if (array_key_exists($annotation['value'], $hobbyList)) {
						$hobbyList[$annotation['value']] = $hobbyList[$annotation['value']] + 1;
					}
					else {
						$hobbyList[$annotation['value']] = 1;
					}
				}
			}
		}

		arsort($hobbyList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$hobbyList = array_slice($hobbyList, 0, $this->subLimit, true);
		}
	}

	# personal 
	// remove the query keys from the list
	$personalKeys = array();
	//if (isset ($this->personalList)) {
	//	$personalKeys = array_keys($this->personalList);
	//}
	/* disabled since version 1.2n
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "businessunitOnly") ) {
		# businessunit
		# $businessunitContext = SearchWordGetWords('businessunit', '');
		$businessunitContext = SearchIndexListWords('businessunit', $indexString);

		foreach (array_keys($businessunitContext) as $peKey) {
			if (trim($businessunitContext[$peKey]['Word']) != "" && ! in_array('businessunit', $personalKeys) ) {
				$businessunitList[$businessunitContext[$peKey]['Word']] = $businessunitContext[$peKey]['Count'];
			}
		}

		arsort($businessunitList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$businessunitList = array_slice($businessunitList, 0, $this->subLimit, true);
		}
	}
	*/

	/* disabled since version 1.2n
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "workplaceOnly") ) {
		# workplace
		# $workplaceContext = SearchWordGetWords('workplace', '');
		$workplaceContext = SearchIndexListWords('workplace', $indexString);

		foreach (array_keys($workplaceContext) as $peKey) {
			if (trim($workplaceContext[$peKey]['Word']) != "" && ! in_array('workplace', $personalKeys) ) {
				$workplaceList[$workplaceContext[$peKey]['Word']] = $workplaceContext[$peKey]['Count'];
			}
		}

		arsort($workplaceList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$workplaceList = array_slice($workplaceList, 0, $this->subLimit, true);
		}
	}
	*/

	# status & my
	if ($this->detail == "all" || $this->detail == "status") {

		$statusList['relevant'] = 8;
		$statusList['open'] = 7;
		$statusList['open_day'] = 6;
		$statusList['open_week'] = 5;
		$statusList['open_month'] = 4;
		$statusList['closed'] = 3;
		$statusList['closed_story'] = 2;
		#$statusList['closed_no_story'] = 1;

		arsort($statusList, SORT_NUMERIC);
		#$statusList = array_slice($statusList, 0, $this->limit, true);
		$this->listSize = count($statusList);
	}

	if ($this->detail == "all" || $this->detail == "my") {

		$myList['act'] = 2;
		$myList['react'] = 1;

		arsort($myList, SORT_NUMERIC);
		#$myList = array_slice($myList, 0, $this->limit, true);
		$this->listSize = count($myList);
	}

	# network
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "networkOnly") ) {
		$annotationList = AnnotationListWithValues('ActAnnotation', 'WHERE ActId in ('.$indexString.') AND AnnotationAttribute = "network"', '', '', GetAnnotationProperties('act'), 1);
		foreach ($annotationList as $annotation) {
			if (trim($annotation['value']) != "") {
				if (array_key_exists($annotation['value'], $networkList)) {
					$networkList[$annotation['value']] = $networkList[$annotation['value']] + 1;
				}
				else {
					$networkList[$annotation['value']] = 1;
				}
			}
		}

		arsort($networkList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$networkList = array_slice($networkList, 0, $this->subLimit, true);
		}
	}

#$time3 = getMicrotime();

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = MysqlCountWithValues("ReferenceId", $table, $where, $order);

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

#$time4 = getMicrotime();
#log2file("****total search time:".($time4 - $time1)."*****index:".($time2 - $time1)."*****filter:".($time3 - $time2)."*****summary:".($time4 - $time3)."*****");

	#
	# Content
	#
	$this->ses['response']['param']['indexList'] = $indexList;

	# suggestion
	$this->ses['response']['param']['suggest'] = $suggest;
	$this->ses['response']['param']['suggestList'] = $suggestList;

	# knowledge
	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;

	# hobby
	$this->ses['response']['param']['hobbyList'] = $hobbyList;

	# personal
	$this->ses['response']['param']['businessunitList'] = $businessunitList;
	$this->ses['response']['param']['workplaceList'] = $workplaceList;

	# tags
	$this->ses['response']['param']['tagList'] = $tagList;

	# status & my
	$this->ses['response']['param']['statusList'] = $statusList;
 	$this->ses['response']['param']['myList'] = $myList;

	# network
	$this->ses['response']['param']['networkList'] = $networkList;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
