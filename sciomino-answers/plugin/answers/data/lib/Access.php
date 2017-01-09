<?php

// ACCESS APP

function AccessAppInsert ($accessApp) {
        global $XCOW_B;

        $accessAppId = 0;

	$accessApp = safeListInsert($accessApp);

        $result = mysql_query("INSERT INTO AccessApp VALUES(NULL, '{$accessApp['name']}', '{$accessApp['key']}')", $XCOW_B['mysql_link']);

        if ($result) {
                $accessAppId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("AccessAppInsert", $XCOW_B['mysql_link']);
	}

        return $accessAppId;
}

function AccessAppList() {
        global $XCOW_B;

	$table = "AccessApp";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return AccessAppListWithValues($table, $where, $order, $limit, $expand);
}

function AccessAppListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$accessAppList = array();

	#
        # Construct QUERY
        #
	$query = "SELECT AccessApp.AccessAppId, AccessApp.AccessAppName, AccessApp.AccessAppKey from $table $where $order $limit";
 	#log2file("AccessAppList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
                 	$accessAppId = $result_row['AccessAppId'];

                	$accessAppList[$accessAppId] = array();
			$accessAppList[$accessAppId]['id'] = $result_row['AccessAppId'];
			$accessAppList[$accessAppId]['name'] = $result_row['AccessAppName'];
			$accessAppList[$accessAppId]['key'] = $result_row['AccessAppKey'];
       		}
        }
	else {
		catchMysqlError("AccessAppListWithValues", $XCOW_B['mysql_link']);
	}

	return $accessAppList;

}

function AccessAppUpdate ($ids, $accessApp) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$accessAppString = "";

	$accessApp = safeListInsert($accessApp);

	# create update string
	foreach ($accessApp as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "AccessAppName='".$value."'";
				break;
			case "key":
				$updateString .= "AccessAppKey='".$value."'";
				break;
			default:
				$updateString .= $attribute."='".$value."'";
				break;
		}
	}

	#
	# go
	#
	if ($updateString != "") {

 		$accessAppString = implode(",",$ids);
                $where = "AccessAppId in ($accessAppString)";
		$result = mysql_query("UPDATE AccessApp SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("AccessAppUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function AccessAppDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$accessAppString = "";

	$accessAppString = implode(",",$ids);
        $where = "AccessAppId in ($accessAppString)";
	$result = mysql_query("DELETE FROM AccessApp WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
       		catchMysqlError("AccessAppDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

?>
