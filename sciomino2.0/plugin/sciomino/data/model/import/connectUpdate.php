<?php

class connectUpdate extends control {

    function Run() {

        global $XCOW_B;
        
	# get all connections
	$connectionList = OauthClientGetAllConnectionsBySession();

	foreach ($connectionList as $session => $connections) {

		$userId = UserApiGetUserFromReference($session);
		$socialNetworkList = ScioMinoApiListSocialNetwork ($userId);

		$twitterId = get_id_from_multi_array($socialNetworkList, 'title', 'twitter');
		$linkedinId = get_id_from_multi_array($socialNetworkList, 'title', 'linkedin');

		echo "User: $userId<br/>";

		# add/update USER DB with new connections
		$seenTwitter = 0;
		$seenLinkedin = 0;
		$newTwitter = array();
		$newLinkedin = array();
		foreach ($connections as $connection) {
			if ($connection['app'] == 'twitter') {
				$newTwitter['title'] = $connection['app'];
				$newTwitter['relation-self'] = $connection['reference'];
				$newTwitter['count-followers'] = 0;
				$newTwitter['count-friends'] = 0;
				$newTwitter['count-statuses'] = 0;

				# TODO: only 180 requests per 15 minutes, 2 solutions
				# - request for more from sciomino frontends
				# - request this info in batches of 100 with users/lookup.json, 180x100 = 18000
				$headers= array();
				$params = array();
				$response = OauthClientGetResponse($session, "twitter", "https://api.twitter.com/1.1/users/show.json?screen_name=".substr($connection['reference'],1), "GET", $headers, $params);

				$feed = json_decode($response, TRUE);
				if (json_last_error() == JSON_ERROR_NONE) {

					$newTwitter['count-followers'] = $feed['followers_count'];
					$newTwitter['count-friends'] = $feed['friends_count'];
					$newTwitter['count-statuses'] = $feed['statuses_count'];

				}
	
				if ($twitterId == 0) {
					# add
					$newId = ScioMinoApiSaveSocialNetwork ($newTwitter, $userId, '1'); 
					echo "Added twitter: $newId<br/>";
				}
				else {
					# update (if necessary)
					if (($newTwitter['relation-self'] != $socialNetworkList[$twitterId]['relation-self']) || ($newTwitter['count-followers'] != $socialNetworkList[$twitterId]['count-followers']) || ($newTwitter['count-friends'] != $socialNetworkList[$twitterId]['count-friends']) || ($newTwitter['count-statuses'] != $socialNetworkList[$twitterId]['count-statuses'])) {
						$updateId = ScioMinoApiUpdateSocialNetwork($newTwitter, $userId, $twitterId);
						echo "Updated twitter: $updateId<br/>";
					}
				}
				$seenTwitter = 1;
			}

			if ($connection['app'] == 'linkedin') {
				$newLinkedin['title'] = $connection['app'];
				$conRef = explode('||', $connection['reference']);
				$newLinkedin['relation-self'] = '';
				$newLinkedin['relation-other'] = '';
				if (count($conRef) == 2) {
					$newLinkedin['relation-self'] = $conRef[1];
					$newLinkedin['relation-other'] = $conRef[0];
				}
				else {
					$newLinkedin['relation-other'] = $conRef[0];
				}
	
				if ($linkedinId == 0) {
					# add
					$newId = ScioMinoApiSaveSocialNetwork ($newLinkedin, $userId, '1'); 
					echo "Added linkedin: $newId<br/>";
				}
				else {
					# update (if necessary)
					if (($newLinkedin['relation-self'] != $socialNetworkList[$linkedinId]['relation-self']) || ($newLinkedin['relation-other'] != $socialNetworkList[$linkedinId]['relation-other'])) {
						$updateId = ScioMinoApiUpdateSocialNetwork($newLinkedin, $userId, $linkedinId);
						echo "Updated linkedin: $updateId<br/>";
					}
				}
				$seenLinkedin = 1;
			}
		}

		#delete connections from USER DB
		if (! $seenTwitter && $twitterId != 0) {
			#delete
			$delId = ScioMinoApiDeleteSocialNetwork($userId, $twitterId);
			echo "Deleted twitter: $delId<br/>";
		}

		if (! $seenLinkedin && $linkedinId != 0) {
			#delete
			$delId = ScioMinoApiDeleteSocialNetwork($userId, $linkedinId);
			echo "Deleted linkedin: $delId<br/>";
		}

	}
    }
}

?>
