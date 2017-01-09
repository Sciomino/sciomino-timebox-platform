<?

class push2api extends control {

    function Run() {

        global $XCOW_B;

		$status = null;
		
		# who?
		$this->id = $this->ses['request']['param']['id'];
		if (! isset($this->id)) {$status = "Nothing to be done: user not set.";}
		
		# about the customer
		# - get customer info
		# - get app info
		$this->customer = $this->ses['request']['param']['customer'];
		$this->customerInfo = $XCOW_B['graph']['customers'][$this->customer];
		$appInfo = GraphSessionGetAppInfo($this->customerInfo["appid"]);

		# make sure the customer resolves in some usefull info
		if (! $status && array_key_exists('network', $appInfo) && $appInfo['network'] != "" ) {

			# get user info from timebox api
			$apiResult = array();
			$userInfo = array();
			$availability = array();
			$apiResult = json_decode(GraphDataLiveUser($appInfo, $this->id), TRUE);
			
			# this timebox call should return one result
			if ($apiResult["content"]["summary"]["completeListSize"] == 1) {
				$userInfo = current($apiResult["content"]["user"]);
				$availability = current($userInfo["availability"]);
				
				# milestone 1
				print_r($availability);

				# now, connect to remote api & write availability
				if ($this->customerInfo['software'] == "carerix") {
					# first, get remote user id
					list($remoteStatus, $remoteInfo) = $this->getCarerixUser($this->customerInfo, $this->id);
					if ($remoteStatus == 0) {
						$status = $remoteInfo[0];
					}
					else {
						# milestone 2
						print_r($remoteInfo);
						
						# second, push availability
						list($remoteStatus2, $remoteInfo2) = $this->putCarerixAvailability($this->customerInfo, $remoteInfo["CREmployee"], $availability);
						if ($remoteStatus2 == 0) {
							$status = $remoteInfo2[0];
						}
						else {
							# milestone 3
							print_r($remoteInfo2);

							$status = "Availability set successful for user ".$this->id. ". Carerix user ".$remoteInfo["CREmployee"];
						}
					}
				}
				
			}
			else {
				$status = "Nothing to be done: user ".$this->id." is not found/unique.";
			}			
		}
		else {
			if (! $status) {
				$status = "Nothing to be done: customer unknown.";
			}
		}
		
		# output
		$this->ses['response']['param']['status'] = $status;
		
		# for debug
		log2file("### PUSHAPI ### push2api: ".$status); 


    }
    
    #
    # get carerix info based on local user=emailaddress
    #
    function getCarerixUser($customer, $user) {

		#####
		# 1 #
		#####
		
		$status = 0;
		$info = array();
		
		# http config & options
		# - get connect stuff from customer
		# - set basic auth
		# - get statements as XML
		$url = $customer['endpoint1']."/qualifier/".urlencode("toUser.emailAddresses.emailAddress=\"".$user."\"");
		# THIS IS FOR DEMO USAGE AND SHOULD BE DISABLED IN PRODUCTION
		# $url = $customer['endpoint1']."/qualifier/".urlencode("toUser.emailAddresses.emailAddress=\"example@example.com\"");
		$options = array(
			'http' => array(
				'header'  => "Content-Type: text/xml\r\n"
							."Authorization: Basic ".base64_encode($customer['user'].":".$customer['password'])."\r\n",
				'method'  => 'GET'
			)
		);

		# get result from carerix
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$resultArray = array();
		
		#####
		# 2 #
		#####

		# analyse result (it's xml)
		if ($result !== FALSE) { 
			# $info = json_decode($result, TRUE);
			$resultObj = new Xml2Php2();
			$resultObj->startProcessing($result, 1);
			$resultArray = $resultObj->getPhpArray();

			$resultCount = 0;
			if (is_array($resultArray["array"][0])) {
				$resultCount = $resultArray["array"][0]["0:Attributes"]["count"];
			}
			
			# there is a result and the result count = 1
			if ($resultCount == 1) {
				$status = 1;
				
				$remoteUser = $resultArray["array"][0]["CREmployee"][0]["0:Attributes"]["id"];
				$info["CREmployee"] = $remoteUser;
			}
			else {
				$info[] = "GET: Carerix API does not return an unique user id for ".$user;
			}
		}
		else {
			$info[] = "GET: Carerix API does not respond";
		}
		
		return array($status, $info);
		
	}

	#
    # put availability data to carerix based on remote user=CREmployee
    #
    function putCarerixAvailability($customer, $user, $availability) {
		
		$status = 0;
		$info = array();
		
		#####
		# 1 #
		#####
		
		# calculate the availability to be set in carerix
		$newDate = "";
		$newHours = "";
		
		# The candidate is available as of the current timestamp
		if ($availability["status"] == "available") {
			$newDate = date("Y-m-d H:i:s", $availability["timestamp"]);
			$newHours = $availability["hours"];
		}
		else {
			# The candidate is available when his current unavailibility expires
			if ($availability["status"] == "unavailable" && $availability["future-status"] == "available") {
				$newDate = date("Y-m-d H:i:s", $availability["until"]);
				$newHours = $availability["future-hours"];
			}
		}
			
		#####
		# 2 #
		#####

		# http config & options
		# - get connect stuff from customer
		# - set data
		# - set basic auth
		# - get statements as XML
		$url = $customer['endpoint2']."/".$user;
		
		$data = "<CREmployee><availableDate>".$newDate."</availableDate><hoursPerWeek>".$newHours."</hoursPerWeek ></CREmployee>"; 

		$options = array(
			'http' => array(
				'header'  => "Content-Type: text/xml\r\n"
							."Authorization: Basic ".base64_encode($customer['user'].":".$customer['password'])."\r\n",
				'method'  => 'PUT',
				'content' => $data
			)
		);

		# get result from carerix
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$resultArray = array();
			
		#####
		# 3 #
		#####
		
		# analyse result (it's xml)
		if ($result !== FALSE) { 
			# $info = json_decode($result, TRUE);
			$resultObj = new Xml2Php2();
			$resultObj->startProcessing($result, 1);
			$resultArray = $resultObj->getPhpArray();
			
			$resultId = $resultArray["CREmployee"][0]["0:Attributes"]["id"];

			# succes if there is a result and the resultId is the same as requested
			if ($user == $resultId) {
				$status = 1;

				$info["availableDate"] = $newDate;
				$info["hoursPerWeek"] = $newHours;
			}
			else {
				$info[] = "PUT: Carerix API does not return the expected response";
			}
		}
		else {
			$info[] = "PUT: Carerix API does not respond";
		}
		
		return array($status, $info);
		
	}
	
}

?>
