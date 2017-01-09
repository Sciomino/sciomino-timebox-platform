<?php
//
// Which Session uses which App?
//

function GraphSessionAppInsert ($type, $sesId, $appId) {
        global $XCOW_B;

        $sesAppId = 0;

        $result = mysql_query("INSERT INTO SessionInApp VALUES(NULL, $type, $sesId, $appId)", $XCOW_B['mysql_link']);

        if ($result) {
			$sesAppId = mysql_insert_id($XCOW_B['mysql_link']);
        }
		else {
			catchMysqlError("GraphSessionAppInsert", $XCOW_B['mysql_link']);
		}

        return $sesAppId;
}

function GraphSessionAppList($sesId) {
        global $XCOW_B;

		$table = "SessionInApp";
		$where = "WHERE SessionId = $sesId";
		$order = "";
		$limit = "";
		$expand = 1;

		return GraphSessionAppListWithValues($table, $where, $order, $limit, $expand);
}

function GraphSessionAppListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

		$sesAppList = array();

		#
        # Construct QUERY
        #
		$query = "SELECT SessionInApp.SessionInAppId, SessionInApp.AppTypeId, SessionInApp.AuthAppId from $table $where $order $limit";
		#log2file("SessionAppList: Query: ".$query);

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if ($result) {
			while ($result_row = mysql_fetch_assoc($result)) {
				$sesAppId = $result_row['SessionInAppId'];

				$sesAppList[$sesAppId] = array();
				$sesAppList[$sesAppId]['id'] = $result_row['AuthAppId'];
				$sesAppList[$sesAppId]['typeId'] = $result_row['AppTypeId'];
			}
		}
		else {
			catchMysqlError("GraphSessionAppListWithValues", $XCOW_B['mysql_link']);
		}
		
		return $sesAppList;
}

function GraphSessionAppDelete ($type, $sesId, $appId) {
        global $XCOW_B;

        $status = NULL;
        $where = "SessionInAppType = $type AND SessionId = $sesId AND AuthAppId = $appId";
		$result = mysql_query("DELETE FROM SessionInApp WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
           	catchMysqlError("GraphSessionAppDelete", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
            $status = "404 Not Found";
        }

	return $status;
}

function GraphSessionGetAppType ($appTypeId) {
        global $XCOW_B;

        $appType = array();

        $result = mysql_query("SELECT AppType.AppTypeName, AppType.AppTypeStartYear, AppType.AppTypeStartMonth From AppType WHERE AppTypeId = $appTypeId", $XCOW_B['mysql_link']);

        if ($result) {
            if (mysql_num_rows($result) ==  1 ) {
                $result_row = mysql_fetch_row($result);
				$appType['typeName'] = $result_row[0];
				$appType['year'] = $result_row[1];
				$appType['month'] = $result_row[2];
            }
        }
		else {
			catchMysqlError("GraphSessionGetAppType", $XCOW_B['mysql_link']);
		}

        return $appType;
}

function GraphSessionGetAppInfo ($appId) {
        global $XCOW_B;

        $appInfo = array();

        $result = mysql_query("SELECT AuthApp.AuthAppName, AuthApp.AuthAppSecret From AuthApp WHERE AuthAppId = $appId", $XCOW_B['mysql_link']);

        if ($result) {
            if (mysql_num_rows($result) ==  1 ) {
                $result_row = mysql_fetch_row($result);
				list($appInfo['name'],$appInfo['network']) = explode(":", $result_row[0],2);
				$appInfo['secret'] = $result_row[1];
            }
        }
		else {
			catchMysqlError("GraphSessionGetAppInfo", $XCOW_B['mysql_link']);
		}

        return $appInfo;
}

//
// UTILS
//

function GraphUtilsNetworkReplace ($stats, $network) {
	$replace = array("UserCount", "UserKnowledgeCount", "UserHobbyCount", "UserTagCount", "UserExperienceCount", "UserPublicationCount", "KnowledgeCount", "HobbyCount", "TagCount", "ProductCount", "CompanyCount", "EducationCount", "EventCount", "UserTwitterCount", "UserLinkedinCount", "BlogCount", "PresentationCount", "WebsiteCount", "OtherPubCount");
	foreach ($replace as $item) {
		$stats[$item] = $stats[$network][get_id_from_multi_array($stats[$network], "label", $item)]['count'];
	}
	return $stats;
}

//
// DATA
//

# gateway to timebox user
function GraphDataLiveUser ($appInfo, $user) {
        global $XCOW_B;
	
		$availabilityData = "";	

		# credentials
		$XCOW_B['availability_api']['auth']  	= 1;       
		$XCOW_B['availability_api']['secret']  	= $appInfo['secret'];
		$XCOW_B['availability_api']['id']    	= $appInfo['name'];
		$XCOW_B['availability_api']['nonce']	= md5(microtime().date("r").mt_rand(11111, 99999));
		$XCOW_B['availability_api']['key']		= sha1($XCOW_B['availability_api']['nonce'].$XCOW_B['availability_api']['id'].$XCOW_B['availability_api']['secret']);
		
		# query
		$availabilityQuery = array();
		$availabilityQuery['group'] = $appInfo['network'];
		$availabilityQuery['view'] = "json";
		$availabilityQuery['format'] = "long";

		$availabilityData = AvailabilityApiUserInfoPlainWithQuery($user, $availabilityQuery);	
		return $availabilityData;
}

# gateway to timebox user list
function GraphDataLiveUserList ($appInfo, $apiParams) {
        global $XCOW_B;
	
		$availabilityData = "";	

		# credentials
		$XCOW_B['availability_api']['auth']  	= 1;       
		$XCOW_B['availability_api']['secret']  	= $appInfo['secret'];
		$XCOW_B['availability_api']['id']    	= $appInfo['name'];
		$XCOW_B['availability_api']['nonce']	= md5(microtime().date("r").mt_rand(11111, 99999));
		$XCOW_B['availability_api']['key']		= sha1($XCOW_B['availability_api']['nonce'].$XCOW_B['availability_api']['id'].$XCOW_B['availability_api']['secret']);
		
		# query
		$availabilityQuery = array();
		$availabilityQuery['group'] = $appInfo['network'];
		$availabilityQuery['view'] = "json";
		if ($apiParams['format'] != "") {
			$availabilityQuery['format'] = $apiParams['format'];
		}
		if ($apiParams['from'] != "") {
			$availabilityQuery['from'] = $apiParams['from'];
		}
		if ($apiParams['offset'] != "") {
			$availabilityQuery['offset'] = $apiParams['offset'];
		}
		if ($apiParams['limit'] != "") {
			$availabilityQuery['limit'] = $apiParams['limit'];
		}

		$availabilityData = AvailabilityApiUserListPlainWithQuery($availabilityQuery);	
		return $availabilityData;
}

function GraphDataFetch ($type, $appId, $appInfo, $update) {
        global $XCOW_B;

		# get dataList from local store
		$dataList = GraphDataList($type, $appId);
		
		# fetch new data if neccessary
		# - always fetch if no local data
		# - always fetch if data older then one day
		# - fetch if NOW is after today at 8:00:00 and the data is before today at 8:00:00
		$localOk = 1;
		if ($update == 1) {
			$localOk = 0;
		}
		else {
			if (count($dataList) == 0) { 
				$localOk = 0;
			}
			else {
				$dataList = current($dataList);
				
				$now = time();
				// check timestamp older than 1 day
				$timestamp = $now - (24 * 60 * 60);
				if ($dataList['timestamp'] < $timestamp ) {
					$localOk = 0;
				}
				// check timestamp before today at 8:00:00
				$timestamp = strtotime('8:00:00');
				if ($dataList['timestamp'] < $timestamp && $now > $timestamp) {
					$localOk = 0;
				}
			}
		}
		// echo "localOk:".$localOk."DataTime:".$dataList['timestamp']."CheckTime:".$timestamp."<br/>\n";
		
		$availabilityData = "";	
		if ($localOk) {
			$availabilityData = $dataList['text'];
		}
		else {
			$XCOW_B['availability_api']['auth']  	= 1;       
			$XCOW_B['availability_api']['secret']  	= $appInfo['secret'];
			$XCOW_B['availability_api']['id']    	= $appInfo['name'];
			$XCOW_B['availability_api']['nonce']	= md5(microtime().date("r").mt_rand(11111, 99999));
			$XCOW_B['availability_api']['key']		= sha1($XCOW_B['availability_api']['nonce'].$XCOW_B['availability_api']['id'].$XCOW_B['availability_api']['secret']);
			
			# query
			# TODO: Loop this to make sure that all data is accounted for (now it's limited by the server limit of 100)
			$availabilityQuery = array();
			$availabilityQuery['group'] = $appInfo['network'];
			$availabilityQuery['view'] = "json";
			$availabilityQuery['format'] = "long";
			$availabilityData = AvailabilityApiUserListPlainWithQuery($availabilityQuery);
		
			# save this data
			GraphDataInsertAndUpdate($availabilityData, $type, $appId); 
		}

		return $availabilityData;
}

function GraphDataFetchTimestamp($type, $appId) {
        global $XCOW_B;

		$timestamp = 0;

		#
        # Construct QUERY
        #
		$query = "SELECT AppData.AppDataTimestamp from AppData where AppData.AppTypeId = $type AND AppData.AuthAppId = $appId";

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if (mysql_num_rows($result) == 1) {
			$result_row = mysql_fetch_assoc($result);
			$timestamp = $result_row['AppDataTimestamp'];
		}
		else {
			catchMysqlError("GraphDataTimestamp", $XCOW_B['mysql_link']);
		}
		
		return $timestamp;
}

function GraphDataInsertAndUpdate ($text, $type, $appId) {
        global $XCOW_B;

        $dataId = 0;
		$timestamp = time();

        $result = mysql_query("INSERT INTO AppData VALUES('$timestamp', '$text', $type, $appId) ON DUPLICATE KEY UPDATE AppDataTimestamp = '{$timestamp}', AppDataText = '{$text}'", $XCOW_B['mysql_link']);

        if ($result) {
			$dataId = mysql_insert_id($XCOW_B['mysql_link']);
		}
		else {
			catchMysqlError("GraphDataInsert", $XCOW_B['mysql_link']);
		}

        return $dataId;
}

function GraphDataList($type, $appId) {
        global $XCOW_B;

		$table = "AppData";
		$where = "WHERE AppTypeId = $type AND AuthAppId = $appId";
		$order = "";
		$limit = "";
		$expand = 1;

		return GraphDataListWithValues($table, $where, $order, $limit, $expand);
}

function GraphDataListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

		$dataList = array();

		#
        # Construct QUERY
        #
		$query = "SELECT AppData.AppDataTimestamp, AppData.AppDataText, AppData.AppTypeId, AppData.AuthAppId from $table $where $order $limit";
		#log2file("GraphDataList: Query: ".$query);

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if ($result) {
			$count = 0;
			while ($result_row = mysql_fetch_assoc($result)) {
				$dataId = $count;

				$dataList[$dataId] = array();
				$dataList[$dataId]['id'] = $dataId;
				$dataList[$dataId]['timestamp'] = $result_row['AppDataTimestamp'];
				$dataList[$dataId]['text'] = $result_row['AppDataText'];
				$dataList[$dataId]['typeId'] = $result_row['AppTypeId'];
				$dataList[$dataId]['appId'] = $result_row['AuthAppId'];
				
				$count++;
			}
		}
		else {
			catchMysqlError("GraphDataListWithValues", $XCOW_B['mysql_link']);
		}
		
		return $dataList;
}

?>
