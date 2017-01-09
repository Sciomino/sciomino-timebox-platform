<?php

// MYSQL

function MysqlCountWithValues ($counter, $table, $where, $order) {
        global $XCOW_B;

	$size = 0;

        $query = "SELECT count($counter) from $table $where $order";
        $result = mysql_query($query, $XCOW_B['mysql_link']);

        if ($result) {
                if (mysql_num_rows($result) > 0 ) {
                        $result_row = mysql_fetch_row($result);
        		$size = $result_row[0];
                }
        }
	
	return $size;
}

// MYSQL SEARCH

function constructWhereWithMatch($field, $match, $expression) {

	$tempWhere = "";

	if ($expression == "exact") { $my_expression = '= "'.$match.'"'; }
	if ($expression == "contains") { $my_expression = 'like "%'.$match.'%"'; }
	if ($expression == "begin") { $my_expression = 'like "'.$match.'%"'; }
	if ($expression == "end") { $my_expression = 'like "%'.$match.'"'; }

	$tempWhere .= "($field $my_expression)";

	return $tempWhere;

}

function constructMultipleWhereWithParam($field, $match, $expression, $type) {

	$inverse = 0;
	$tempWhere = "";

	if ($expression == "all") { $my_expression = 'AND'; }
	if ($expression == "any") { $my_expression = 'OR'; }
	if ($expression == "none") { $my_expression = 'OR'; $inverse = 1;}

	foreach (($match) as $Key => $Val) {
		#echo "KEY:".$Key.",VAL:".$Val;
		if ($tempWhere != "") { $tempWhere .= " ".$my_expression." "; }		
		if ($type == "stringExact") {
			$tempWhere .= $field." = \"".$Key."\"";
		}
		if ($type == "stringContains") {
			$tempWhere .= $field." like  \"%".$Key."%\"";
		}
		if ($type == "stringBegin") {
			$tempWhere .= $field." like  \"".$Key."%\"";
		}
		if ($type == "stringEnd") {
			$tempWhere .= $field." like  \"%".$Key."\"";
		}
		if ($type == "number") {
			$tempWhere .= $field." = ".$Key;
		}
		if ($type == "numberlist") {
			$tempWhere .= $Val;
		}
	}

	$tempWhere = "(".$tempWhere.")";
	if ($inverse) { 
		$tempWhere = "(NOT ".$tempWhere.")";
	}

	return $tempWhere;

}

// MYSQL Index

function SearchIndexInsert ($id, $wordId) {
        global $XCOW_B;

        $indexId = 0;

        $result = mysql_query("INSERT INTO SearchIndex VALUES($id, $wordId)", $XCOW_B['mysql_link']);

        if ($result) {
                $indexId = mysql_insert_id($XCOW_B['mysql_link']);
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
        $query = "SELECT DISTINCT SearchIndex.ReferenceId FROM $table $where $order $limit";
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
	
	return $wordIdList;

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

	// update index with context words
	foreach ($wordsWithContext as $item) {
		$word = $item[0];
		$context = $item[1];
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
	
	// always ok?
	return $id;
}

function SearchIndexDelete ($id) {
        global $XCOW_B;

        $status = NULL;

	$result = mysql_query("DELETE FROM SearchIndex WHERE ReferenceId = $id", $XCOW_B['mysql_link']);

     	if (! $result) {
             	echoMysqlError(mysql_errno(), mysql_error());
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

	$result = mysql_query("SELECT SearchWordId FROM SearchWord WHERE SearchWordWord = '$word' AND SearchWordContext = '$context'", $XCOW_B['mysql_link']);

        if ($result) {
                if (mysql_num_rows($result) > 0 ) {
                        $result_row = mysql_fetch_row($result);
        		$wordId = $result_row[0];
                }
        }

	return ($wordId);
}

function SearchWordGetContext($words) {
        global $XCOW_B;

	$contextList = array();

	// construct where
	$where = "";
	foreach ($words as $word) {
                if ($where != "") { $where .= " OR "; }
		$where .= "SearchWordWord LIKE '$word%'";
	}

	$result = mysql_query("SELECT SearchWordWord, SearchWordContext, SearchWordCount FROM SearchWord WHERE (NOT SearchWordContext = '' ) AND ".$where, $XCOW_B['mysql_link']);

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

	return ($contextList);
}

function SearchWordGetWords($context, $startsWith) {
        global $XCOW_B;

	$contextList = array();

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

	return ($contextList);
}

function SearchWordInsert ($word, $context) {
        global $XCOW_B;

        $wordId = 0;

        $result = mysql_query("INSERT INTO SearchWord VALUES(NULL, '$word', '$context', 1)", $XCOW_B['mysql_link']);

        if ($result) {
                $wordId = mysql_insert_id($XCOW_B['mysql_link']);
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

        return $counter;
}

?>
