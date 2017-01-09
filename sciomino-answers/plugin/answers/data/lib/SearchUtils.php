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
	else {
		catchMysqlError("MysqlCountWithValues", $XCOW_B['mysql_link']);
	}
	
	return $size;
}

// MYSQL SEARCH

function constructWhereWithMatch($field, $match, $expression) {

	$tempWhere = "";

	$match = safeInsert($match);
	if ($expression == "exact") { $my_expression = '= "'.$match.'"'; }
	if ($expression == "contains") { $my_expression = 'like "%'.$match.'%"'; }
	if ($expression == "begin") { $my_expression = 'like "'.$match.'%"'; }
	if ($expression == "end") { $my_expression = 'like "%'.$match.'"'; }
	if ($expression == "not") { $my_expression = '!= "'.$match.'"'; }

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
		$Key = safeInsert($Key);
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

?>
