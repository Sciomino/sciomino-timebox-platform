<?php

// ANSWERS

function ActInsert ($act, $reference) {
        global $XCOW_B;

        $actId = 0;
	$timestamp = time();

	$act = safeListInsert($act);

        $result = mysql_query("INSERT INTO Act VALUES(NULL, '{$act['description']}', '$timestamp', '{$act['expiration']}', {$act['active']}, {$act['parent']}, '$reference')", $XCOW_B['mysql_link']);

        if ($result) {
                $actId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("ActInsert", $XCOW_B['mysql_link']);
	}

        return $actId;
}

function ActList() {
        global $XCOW_B;

	$table = "Act";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return ActListWithValues($table, $where, $order, $limit, $expand);
}

function ActListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$actList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT Act.ActId, Act.ActDescription, Act.ActTimestamp, Act.ActExpiration, Act.ActActive, Act.ActParent, Act.Reference from $table $where $order $limit";
	#log2file("ActList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

			$actId = $result_row['ActId'];
	
                	$actList[$actId] = array();
                	$actList[$actId]['id'] = $actId;
                	$actList[$actId]['description'] = $result_row['ActDescription'];
                	$actList[$actId]['timestamp'] = $result_row['ActTimestamp'];
                	$actList[$actId]['expiration'] = $result_row['ActExpiration'];
                	$actList[$actId]['active'] = $result_row['ActActive'];
                	$actList[$actId]['parent'] = $result_row['ActParent'];
                	$actList[$actId]['reference'] = $result_row['Reference'];

			$actList[$actId]['annotation'] = array();
			$actList[$actId]['profile'] = array();
			$actList[$actId]['review'] = array();
			$actList[$actId]['mailblock'] = array();
			if ($expand) {
				$actList[$actId]['annotation'] = AnnotationList('act', $actId);
				$actList[$actId]['profile'] = ProfileList('act', $actId);
				$actList[$actId]['review'] = ReviewList($actId);
				$actList[$actId]['mailblock'] = MailblockList($actId);
			}
        	}
        }
	else {
		catchMysqlError("ActListWithValues", $XCOW_B['mysql_link']);
	}

	return $actList;
}

function ActUpdate ($ids, $act) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$actString = "";

	$act = safeListInsert($act);

	# create update string
	foreach ($act as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "description":
				$updateString .= "ActDescription='".$value."'";
				break;
			case "expiration":
				$updateString .= "ActExpiration"."='".$value."'";
				break;
			case "active":
				$updateString .= "ActActive"."='".$value."'";
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

 		$actString = implode(",",$ids);
                $where = "ActId in ($actString)";
		$result = mysql_query("UPDATE Act SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("ActUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function ActDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$actString = "";

	$actString = implode(",",$ids);
        $where = "ActId in ($actString)";
	$result = mysql_query("DELETE FROM Act WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
		catchMysqlError("ActDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// REVIEW

function ReviewInsert ($review, $act, $reference) {
        global $XCOW_B;

        $reviewId = 0;

	$review = safeListInsert($review);

        $result = mysql_query("INSERT INTO ActReview VALUES(NULL, {$review['score']}, '$reference', $act)", $XCOW_B['mysql_link']);

        if ($result) {
                $reviewId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("ReviewInsert", $XCOW_B['mysql_link']);
	}

        return $reviewId;
}

function ReviewList($act) {
        global $XCOW_B;

	$table = "ActReview";
	$where = "WHERE ActId = ".$act;
	$order = "";
	$limit = "";
	$expand = 0;

	return ReviewListWithValues($table, $where, $order, $limit, $expand);
}

function ReviewListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$reviewList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT ActReview.ActReviewId, ActReview.ActReviewScore, ActReview.Reference, ActReview.ActId from $table $where $order $limit";
	#log2file("ReviewList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

			$reviewId = $result_row['ActReviewId'];
	
                	$reviewList[$reviewId] = array();
                	$reviewList[$reviewId]['id'] = $reviewId;
                	$reviewList[$reviewId]['score'] = $result_row['ActReviewScore'];
                	$reviewList[$reviewId]['reference'] = $result_row['Reference'];
	               	$reviewList[$reviewId]['act'] = $result_row['ActId'];

			if ($expand) {
			}
        	}
        }
	else {
		catchMysqlError("ReviewListWithValues", $XCOW_B['mysql_link']);
	}

	return $reviewList;
}

function ReviewUpdate ($ids, $review) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$reviewString = "";

	$review = safeListInsert($review);

	# create update string
	foreach ($review as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "score":
				$updateString .= "ActReviewScore='".$value."'";
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

 		$reviewString = implode(",",$ids);
                $where = "ActReviewId in ($reviewString)";
		$result = mysql_query("UPDATE ActReview SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
			catchMysqlError("ReviewUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function ReviewDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$reviewString = "";

	$reviewString = implode(",",$ids);
        $where = "ActReviewId in ($reviewString)";
	$result = mysql_query("DELETE FROM ActReview WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
 		catchMysqlError("ReviewDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// MAILBLOCK

function MailblockInsert ($act, $reference) {
	global $XCOW_B;

	$mailblockId = 0;

	$result = mysql_query("INSERT INTO ActMailblock VALUES(NULL, '$reference', $act)", $XCOW_B['mysql_link']);
	if ($result) {
			$mailblockId = mysql_insert_id($XCOW_B['mysql_link']);
	}
	else {
		catchMysqlError("MailblockInsert", $XCOW_B['mysql_link']);
	}
	
    return $mailblockId;
}

function MailblockList($act) {
    global $XCOW_B;

	$table = "ActMailblock";
	$where = "WHERE ActId = ".$act;
	$order = "";
	$limit = "";
	$expand = 0;

	return MailblockListWithValues($table, $where, $order, $limit, $expand);
}

function MailblockListWithValues($table, $where, $order, $limit, $expand) {
	global $XCOW_B;

	$mailblockList = array();

	#
	# Construct QUERY
	#
	$query = "SELECT ActMailblock.ActMailblockId, ActMailblock.Reference, ActMailblock.ActId from $table $where $order $limit";

	#
	# SELECT
	#
	$result = mysql_query("$query", $XCOW_B['mysql_link']);
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

			$mailblockId = $result_row['ActMailblockId'];
	
			$mailblockList[$mailblockId] = array();
			$mailblockList[$mailblockId]['id'] = $mailblockId;
			$mailblockList[$mailblockId]['reference'] = $result_row['Reference'];
			$mailblockList[$mailblockId]['act'] = $result_row['ActId'];

      	}
    }
	else {
		catchMysqlError("MailblockListWithValues", $XCOW_B['mysql_link']);
	}

	return $mailblockList;
}

function MailblockDelete ($ids) {
	global $XCOW_B;

	$status = NULL;
	$mailblockString = "";
	$mailblockString = implode(",",$ids);
	$where = "ActMailblockId in ($mailblockString)";

	$result = mysql_query("DELETE FROM ActMailblock WHERE $where", $XCOW_B['mysql_link']);
	if (! $result) {
		catchMysqlError("MailblockDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
	}
	if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			$status = "404 Not Found";
	}

	return $status;
}

?>
