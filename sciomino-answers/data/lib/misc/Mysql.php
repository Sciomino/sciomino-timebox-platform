<?php

# Open database connection
function openMysqlDb($db) {
        global $XCOW_B;

	$XCOW_B['mysql_link'] = mysql_connect($XCOW_B[$db]['mysql_host'], $XCOW_B[$db]['mysql_user'], $XCOW_B[$db]['mysql_pass']);
	mysql_select_db($XCOW_B[$db]['mysql_db'], $XCOW_B['mysql_link']);
	mysql_query("SET NAMES 'utf8';", $XCOW_B['mysql_link']);
}

# close database
function closeMysqlDb() {
        global $XCOW_B;

	mysql_close($XCOW_B['mysql_link']);
}

function safeInsert($value){
	return mysql_real_escape_string(trim($value));
} 

function safeListInsert($list) {
	foreach ($list as $key => $val) {
		$list[$key] = mysql_real_escape_string(trim($val));
	}
	return $list;
}

?>
