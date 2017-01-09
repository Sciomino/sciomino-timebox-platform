<?php

//
// Get SuggestList from different sources
//
function getConnectorSuggestList($type, $query) {

	global $XCOW_B;
	$suggestList = array();

	$url = $XCOW_B['connect_api']['connection'][$type]['suggestUrl'];
	$expiration = $XCOW_B['connect_api']['connection'][$type]['expiration'];

	// in cache?
	$cacheUrl = $url.$query;
	$cache = readConnectionCache($expiration, $cacheUrl);

	switch ($type) {
		// WIKIPEDIA
		case "wikipedia":
		case "wikipedia-en":
			$body = "";
			if ($cache['status'] == 1) {
				$body = $cache['content'];
			}
			else {
				$headers = array();
				$headers[] = "User-Agent: ScioMino-Connect/1.0 (http://www.sciomino.com/)";
				$params = array();
				$params['action'] = "opensearch";
				$params['format'] = "xml";
				$params['search'] = $query;
				$response = postResponse($url, $headers, $params);
				$body = $response[1];

				writeConnectionCacheEntry($cacheUrl, $body);
			}

			// get the xml list
			$xml = new SimpleXMLElement($body);

			$count = 1;
			foreach ($xml->Section->Item as $item) {
				$suggestList[$count] = array();
				$suggestList[$count]['connectName'] = (string) $item->Text;
				//$suggestList[$count]['connectUrl'] = (string) $item->Url;
				//$suggestList[$count]['connectDescription'] = (string) $item->Description;
				$count++;
			}
			break;
		default:
			break;
	}

	return $suggestList;
}

//
// Get ViewList from different sources
//
function getConnectorViewList($type, $name) {

	global $XCOW_B;
	$viewList = array();

	$url = $XCOW_B['connect_api']['connection'][$type]['viewUrl'];
	$expiration = $XCOW_B['connect_api']['connection'][$type]['expiration'];

	// in cache?
	$cacheUrl = $url.$name;
	$cache = readConnectionCache($expiration, $cacheUrl);
	
	switch ($type) {
		// STATUS/FEED, $name is full url
		case "status":
		case "feed":
			// general status/feed
			$url .= $name;

			$response = "";
			if ($cache['status'] == 1) {
				$response = $cache['content'];
			}
			else {
				$response = getResponse($url);

				writeConnectionCacheEntry($cacheUrl, $response);
			}

			$viewList[1] = array();
			$viewList[1]['connectName'] = $name;
			$viewList[1]['url'] = $url;
			$viewList[1]['description'] = $response;

			break;
		// WIKIPEDIA
		case "wikipedia":
		case "wikipedia-en":
			// wiki api methode
			/*
			$headers = array();
			$headers[] = "User-Agent: ScioMino-Connect/1.0 (http://www.sciomino.com/)";
			$params = array();
			$params['action'] = "parse";
			$params['prop'] = "text";
			$params['format'] = "xml";
			$params['page'] = $this->name;
			$body = postResponse($url, $headers, $params);
			print_r ($body);
			*/

			// wiki page render methode
			$url .= $name;

			$description = "";
			if ($cache['status'] == 1) {
				$description = $cache['content'];
			}
			else {
				$headers = array();
				$headers[] = "User-Agent: ScioMino-Connect/1.0 (http://www.sciomino.com/)";
				$response = getResponseWithHeader($url, $headers);
				$body = $response[1];

				// get the first <p>
				$pos1 = strpos($body, "<p>");
				$pos2 = strpos($body, "</p>") + 4;
				$description = substr($body, $pos1, ($pos2 - $pos1));

				writeConnectionCacheEntry($cacheUrl, $description);
			}

			$publicUrl = $url;
			if ($XCOW_B['connect_api']['connection'][$type]['publicUrl'] != '') {
				$publicUrl = $XCOW_B['connect_api']['connection'][$type]['publicUrl'].$name;
			}
			$viewList[1] = array();
			$viewList[1]['connectName'] = $name;
			$viewList[1]['url'] = $publicUrl;
			$viewList[1]['description'] = $description;

			break;
		default:
			break;
	}

	return $viewList;
}

#
# CACHE
# - needs garbage collect (via cron) to remove old entries
# - what about field: Id, is int, should be bigint
#
function readConnectionCache ($expiration, $url) {
        global $XCOW_B;

	$cache = array();
	$cache['status'] = 0;
	$cache['content'] = "";

	$result = mysql_query("SELECT ConnectionCacheId, ConnectionCacheContent, ConnectionCacheTimestamp FROM ConnectionCache Where ConnectionCacheUrl = '$url'", $XCOW_B['mysql_link']);

	if (mysql_num_rows($result) == 1) {
		$result_row = mysql_fetch_assoc($result);

		if ($result_row['ConnectionCacheTimestamp'] > (time() - $expiration)) {
			$cache['status'] = 1;
			$cache['content'] = $result_row['ConnectionCacheContent'];
		}
		else {
			# remove old entry
			deleteConnectionCacheEntry($result_row['ConnectionCacheId']);
		}
	}

	return ($cache);

}

function writeConnectionCacheEntry ($url, $content) {
        global $XCOW_B;

	$url = safeInsert($url);
	$content = safeInsert($content);
	$timestamp = time();

	$result = mysql_query("INSERT INTO ConnectionCache VALUES(NULL, '$url', '$content', '$timestamp')", $XCOW_B['mysql_link']);

	if ($result) {
		return mysql_insert_id($XCOW_B['mysql_link']);
	}
	else {
		return 0;
	}

}

function deleteConnectionCacheEntry ($id) {
        global $XCOW_B;

	$result = mysql_query("DELETE FROM ConnectionCache WHERE ConnectionCacheId = $id", $XCOW_B['mysql_link']);

	return 1;

}

?>
