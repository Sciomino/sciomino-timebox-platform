<?php

// ACTIVITY

function AvailabilityActivityInsert ($activity, $appId) {
        global $XCOW_B;

        $activityId = 0;
		$timestamp = time();

		$activity = safeListInsert($activity);

        $result = mysql_query("INSERT INTO AuthAppActivity VALUES(NULL, '$timestamp', '{$activity['title']}', '{$activity['description']}', {$activity['priority']}, '{$activity['url']}', $appId)", $XCOW_B['mysql_link']);

        if ($result) {
                $activityId = mysql_insert_id($XCOW_B['mysql_link']);
        }
		else {
			catchMysqlError("AvailabilityActivityInsert", $XCOW_B['mysql_link']);
		}

        return $activityId;
}

function AvailabilityActivityList($appId) {
        global $XCOW_B;

		$table = "AuthAppActivity";
		$where = "WHERE AuthAppId = $appId";
		$order = "";
		$limit = "";
		$expand = 1;

		return AvailabilityActivityListWithValues($table, $where, $order, $limit, $expand);
}

function AvailabilityActivityListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

		$activityList = array();

		#
        # Construct QUERY
        #
        $query = "SELECT AuthAppActivity.AuthAppActivityId, AuthAppActivity.AuthAppActivityTimestamp, AuthAppActivity.AuthAppActivityTitle, AuthAppActivity.AuthAppActivityDescription, AuthAppActivity.AuthAppActivityPriority, AuthAppActivity.AuthAppActivityUrl, AuthAppActivity.AuthAppId from $table $where $order $limit";
		#log2file("ActivityList: Query: ".$query);

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if ($result) {
			while ($result_row = mysql_fetch_assoc($result)) {

				$activityId = $result_row['AuthAppActivityId'];
				$appId = $result_row['AuthAppId'];

				$activityList[$activityId] = array();
				$activityList[$activityId]['id'] = $result_row['AuthAppActivityId'];
				$activityList[$activityId]['timestamp'] = $result_row['AuthAppActivityTimestamp'];
				$activityList[$activityId]['title'] = $result_row['AuthAppActivityTitle'];
				$activityList[$activityId]['description'] = $result_row['AuthAppActivityDescription'];
				$activityList[$activityId]['priority'] = $result_row['AuthAppActivityPriority'];
				$activityList[$activityId]['url'] = $result_row['AuthAppActivityUrl'];
				$activityList[$activityId]['appId'] = $result_row['AuthAppId'];
			}
		}
		else {
			catchMysqlError("AvailabilityActivityListWithValues", $XCOW_B['mysql_link']);
		}

		return $activityList;

}

function AvailabilityActivityUpdate ($ids, $activity) {
        global $XCOW_B;

        $status = NULL;
		$updateString = "";
		$activityString = "";

		$activity = safeListInsert($activity);

		# create update string
		foreach ($activity as $attribute => $value) {
			if ($updateString != "") { $updateString .= ","; }

			switch ($attribute) {
				case "title":
					$updateString .= "AuthAppActivityTitle='".$value."'";
					break;
				case "description":
					$updateString .= "AuthAppActivityDescription='".$value."'";
					break;
				case "priority":
					$updateString .= "AuthAppActivityPriority='".$value."'";
					break;
				case "url":
					$updateString .= "AuthAppActivityUrl='".$value."'";
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

			$activityString = implode(",",$ids);
			$where = "AuthAppActivityId in ($activityString)";
			$result = mysql_query("UPDATE AuthAppActivity SET $updateString WHERE $where", $XCOW_B['mysql_link']);

			if (! $result) {
					catchMysqlError("AvailabilityActivityUpdate", $XCOW_B['mysql_link']);
					$status = "500 Internal Error";
			}
			elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
					# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                    # $status = "404 Not Found";
            }

	}

	return $status;
}

function AvailabilityActivityDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
		$activityString = "";

		$activityString = implode(",",$ids);
        $where = "AuthAppActivityId in ($activityString)";
		$result = mysql_query("DELETE FROM AuthAppActivity WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("AvailabilityActivityDelete", $XCOW_B['mysql_link']);
				$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

		return $status;
}

// USAGE

function AvailabilityUsageInsertAndUpdate ($usage, $groupId) {
        global $XCOW_B;

		$datetime = new DateTime('NOW');
 		$usage['year'] = $datetime->format('Y');
		$usage['month'] = $datetime->format('m');
		$usage['day'] = $datetime->format('d');

		$usage = safeListInsert($usage);

        $result = mysql_query("INSERT INTO AuthAppUsage VALUES($groupId, '{$usage['year']}', '{$usage['month']}', {$usage['day']}, '{$usage['count']}') ON DUPLICATE KEY UPDATE AuthAppUsageCount = AuthAppUsageCount + {$usage['count']}", $XCOW_B['mysql_link']);

        if (! $result) {
			catchMysqlError("AvailabilityUsageInsertAndUpdate", $XCOW_B['mysql_link']);
		}
}


function AvailabilityUsageList($groupId) {
        global $XCOW_B;

		$table = "AuthAppUsage";
		$where = "WHERE AuthAppGroupId = $groupId";
		$order = "";
		$limit = "";
		$expand = 1;

		return AvailabilityUsageListWithValues($table, $where, $order, $limit, $expand);
}

function AvailabilityUsageListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

		$activityList = array();

		#
        # Construct QUERY
        #
        $query = "SELECT AuthAppUsage.AuthAppGroupId, AuthAppUsage.AuthAppUsageYear, AuthAppUsage.AuthAppUsageMonth, AuthAppUsage.AuthAppUsageDay, AuthAppUsage.AuthAppUsageCount from $table $where $order $limit";
		#log2file("UsageList: Query: ".$query);

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		$count = 1;
		if ($result) {
			while ($result_row = mysql_fetch_assoc($result)) {

				$activityList[$count] = array();
				$activityList[$count]['groupId'] = $result_row['AuthAppGroupId'];
				$activityList[$count]['year'] = $result_row['AuthAppUsageYear'];
				$activityList[$count]['month'] = $result_row['AuthAppUsageMonth'];
				$activityList[$count]['day'] = $result_row['AuthAppUsageDay'];
				$activityList[$count]['count'] = $result_row['AuthAppUsageCount'];
				
				$count++;
			}
		}
		else {
			catchMysqlError("AvailabilityUsageListWithValues", $XCOW_B['mysql_link']);
		}

		return $activityList;

}

?>
