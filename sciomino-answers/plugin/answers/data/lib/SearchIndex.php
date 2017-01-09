<?php 

// MYSQL Index

function SearchIndexInsert ($id, $wordId) {
        global $XCOW_B;

        $indexId = 0;

        $result = mysql_query("INSERT INTO SearchIndex VALUES($id, $wordId)", $XCOW_B['mysql_link']);

        if ($result) {
                $indexId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("SearchIndexInsert", $XCOW_B['mysql_link']);
	}

        return $indexId;
}

function SearchIndexList($word) {

	$table = "SearchIndex, SearchWord";
	$where = "WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWord.SearchWordWord = '$word'";
	$order = "";
	$limit = "";
	$expand = 1;

	return SearchIndexListWithValues($table, $where, $order, $limit, $expand);
}

function SearchIndexListWithValues($table, $where, $order, $limit, $expand) {
 
        global $XCOW_B;

	$indexList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT ReferenceId FROM $table $where $order $limit";
#echo $query;
 	#log2file("SearchIndexList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$indexList[] = $result_row['ReferenceId'];
       		}
        }
	else {
		catchMysqlError("SearchIndexListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $indexList;

}

function SearchIndexListWordId($id) {
 
        global $XCOW_B;

	$wordIdList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT DISTINCT SearchIndex.SearchWordId FROM SearchIndex WHERE SearchIndex.ReferenceId = $id";
 	#log2file("SearchIndexList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$wordIdList[] = $result_row['SearchWordId'];
       		}
        }
	else {
		catchMysqlError("SearchIndexListWordId", $XCOW_B['mysql_link']);
	}
	
	return $wordIdList;

}

function SearchIndexListWords($context, $acts) {
 
        global $XCOW_B;

	$wordList = array();

	#
        # Construct QUERY
        #
	$context = safeInsert($context);
        $query = "SELECT DISTINCT SearchWordWord FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWord.SearchWordContext = '$context' AND SearchIndex.ReferenceId in ($acts)";
 	#log2file("SearchIndexList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	$count = 0;
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$wordList[$count] = array();
			$wordList[$count]['Word'] = $result_row['SearchWordWord'];
			$wordList[$count]['Count'] = 1;
			$count++;
       		}
        }
	else {
		catchMysqlError("SearchIndexListWords", $XCOW_B['mysql_link']);
	}
	
	return $wordList;

}

function SearchIndexUpdate ($id, $words, $wordsWithContext) {
        global $XCOW_B;

	$wordId = 0;

	// delete the previous index for this user
	$wordIdList = SearchIndexListWordId($id);
	SearchWordDownList($wordIdList);
	SearchIndexDelete($id);

	// update index with words
	foreach ($words as $word) {
		if (trim($word) != "") {
			$wordId = SearchWordGetId($word, '');
			if ($wordId == 0) {
				// new word
				$wordId = SearchWordInsert($word, '');
			
			}
			else {
				$count = SearchWordUp($wordId);
			}
			SearchIndexInsert($id, $wordId);
		}
	}

	// update index with context words
	foreach ($wordsWithContext as $item) {
		$word = $item[0];
		$context = $item[1];
		if (trim($word) != "") {
			$wordId = SearchWordGetId($word, $context);
			if ($wordId == 0) {
				// new word
				$wordId = SearchWordInsert($word, $context);
			
			}
			else {
				$count = SearchWordUp($wordId);
			}
			SearchIndexInsert($id, $wordId);
		}
	}
	
	// always ok?
	return $id;
}

function SearchIndexDelete ($id) {
        global $XCOW_B;

        $status = NULL;

	$result = mysql_query("DELETE FROM SearchIndex WHERE ReferenceId = $id", $XCOW_B['mysql_link']);

     	if (! $result) {
		catchMysqlError("SearchIndexDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// MYSQL Index Words

function SearchWordGetId($word, $context) {
        global $XCOW_B;

	$wordId = 0;

	$word = safeInsert($word);
	$context = safeInsert($context);
	$result = mysql_query("SELECT SearchWordId FROM SearchWord WHERE SearchWordWord = '$word' AND SearchWordContext = '$context'", $XCOW_B['mysql_link']);

        if ($result) {
                if (mysql_num_rows($result) > 0 ) {
                        $result_row = mysql_fetch_row($result);
        		$wordId = $result_row[0];
                }
        }
	else {
		catchMysqlError("SearchWordGetId", $XCOW_B['mysql_link']);
	}

	return ($wordId);
}

function SearchWordGetContext($words) {
        global $XCOW_B;

	$contextList = array();

	// construct where
	$where = "";
	foreach ($words as $word) {
		$word = safeInsert($word);
                if ($where != "") { $where .= " OR "; }
		$where .= "SearchWordWord LIKE '$word%'";
	}

	$result = mysql_query("SELECT SearchWordWord, SearchWordContext, SearchWordCount FROM SearchWord WHERE (NOT SearchWordContext = '') AND (".$where.")", $XCOW_B['mysql_link']);

	$count = 0;
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$contextList[$count] = array();
			$contextList[$count]['Word'] = $result_row['SearchWordWord'];
			$contextList[$count]['Context'] = $result_row['SearchWordContext'];
			$contextList[$count]['Count'] = $result_row['SearchWordCount'];
			$count++;
       		}
        }
	else {
		catchMysqlError("SearchWordGetContext", $XCOW_B['mysql_link']);
	}

	return ($contextList);
}

function SearchWordGetWords($context, $startsWith) {
        global $XCOW_B;

	$contextList = array();

	$startsWith = safeInsert($startsWith);
	$context = safeInsert($context);
	$result = mysql_query("SELECT SearchWordWord, SearchWordContext, SearchWordCount FROM SearchWord WHERE SearchWordContext = '".$context."' AND SearchWordWord like '".$startsWith."%'", $XCOW_B['mysql_link']);

	$count = 0;
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$contextList[$count] = array();
			$contextList[$count]['Word'] = $result_row['SearchWordWord'];
			$contextList[$count]['Context'] = $result_row['SearchWordContext'];
			$contextList[$count]['Count'] = $result_row['SearchWordCount'];
			$count++;
       		}
        }
	else {
		catchMysqlError("SearchWordGetWords", $XCOW_B['mysql_link']);
	}

	return ($contextList);
}

function SearchWordInsert ($word, $context) {
        global $XCOW_B;

        $wordId = 0;

	$word = safeInsert($word);
	$context = safeInsert($context);
        $result = mysql_query("INSERT INTO SearchWord VALUES(NULL, '$word', '$context', 1)", $XCOW_B['mysql_link']);

        if ($result) {
                $wordId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("SearchWordInsert", $XCOW_B['mysql_link']);
	}

        return $wordId;
}

function SearchWordUp ($wordId) {
        global $XCOW_B;

        $counter = 0;

        $result = mysql_query("UPDATE SearchWord SET SearchWordCount = SearchWordCount + 1 WHERE SearchWordId = $wordId", $XCOW_B['mysql_link']);

        if ($result) {
     		$counter = $result;
        }
	else {
		catchMysqlError("SearchWordUp", $XCOW_B['mysql_link']);
	}

        return $counter;
}

function SearchWordDownList ($wordIdList) {
        global $XCOW_B;

	$wordIdListString = implode(',', $wordIdList);
        $counter = 0;

        $result = mysql_query("UPDATE SearchWord SET SearchWordCount = SearchWordCount - 1 WHERE SearchWordId in (".$wordIdListString.")", $XCOW_B['mysql_link']);

        if ($result) {
     		$counter = $result;
        }
	else {
		catchMysqlError("SearchWordDownList", $XCOW_B['mysql_link']);
	}

	# delete non used words
 	$result = mysql_query("DELETE FROM SearchWord WHERE SearchWordCount = 0 AND SearchWordId in (".$wordIdListString.")", $XCOW_B['mysql_link']);

        return $counter;
}

?>
