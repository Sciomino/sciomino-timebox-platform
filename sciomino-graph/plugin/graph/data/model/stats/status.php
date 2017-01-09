<?

class statsStatus extends control {

    function Run() {

        global $XCOW_B;
        
        $this->id = $this->ses['id'];
        $this->app = $this->ses['request']['param']['app'];
        $this->year = $this->ses['request']['param']['year'];
        $this->month = $this->ses['request']['param']['month'];
        
        $myApps = GraphSessionAppList($this->id);
        $appTypeId = '';
        
        $access = 0;
        foreach ($myApps as $key => $val) {
			if ($val['id'] == $this->app) {
				$appTypeId = $val['typeId'];
				$access = 1;
				break;
			}
		}
		
		if ($access) {

			# which app
			$appInfo = array();
			$appInfo = GraphSessionGetAppInfo($this->app);
			$appType = array();
			$appType = GraphSessionGetAppType($appTypeId);

			$XCOW_B['user_api']['auth']  	= 1;       
			$XCOW_B['user_api']['secret']  	= $appInfo['secret'];
			$XCOW_B['user_api']['id']    	= $appInfo['name'];
			$XCOW_B['user_api']['nonce']	= md5(microtime().date("r").mt_rand(11111, 99999));
			$XCOW_B['user_api']['key']		= sha1($XCOW_B['user_api']['nonce'].$XCOW_B['user_api']['id'].$XCOW_B['user_api']['secret']);
			
			# which stats?
			$year = 0;
			$month = 0;
			$day = 0;

			$today = getdate();

			# override by params
			# - doesn't handle future dates, should check...
			if (isset($this->year) && isset($this->month)) {
				$year = $this->year;
				$month = $this->month;
				# set day to last day of the month (skip 31)
				if ($month != 2) {
					$day = 30;
				}
				else {
					# schrikkeljaren doen we niet aan :-)
					$day = 28;
				} 
			}
			else {
				$year = $today['year'];
				$month = $today['mon'];
				$day = $today['mday'];
			}
			
			$dayList = array(1,8,15,22,27);
			$statsList = array();
			foreach ($dayList as $d) {
				if ($d < $day) { 
					$from = mktime(0,0,0, $month, $d, $year);
					$to = mktime(23,59,59, $month, $d, $year);
					#echo "Y:".$year.":M:".$month.":D:".$d.":FROM:".$from.":TO:".$to;
					$query = "from=".$from."&to=".$to;
					$statsList[] = current(UserApiListStatsWithQuery($query));
				}
			}
			# print_r($statsList);
			
			# substitute network
			if ($appInfo['network'] != "") {
				$groupList = UserApiGroupListWithQuery("name=".urlencode($appInfo['network']));
				$network = "network_".key($groupList);
				foreach($statsList as $v) {
					$statsList[$i] = GraphUtilsNetworkReplace ($statsList[$i], $network);
					$i++;
				}
			}
			
			# paginate
			$this->ses['response']['param']['today'] = $today;

			$this->ses['response']['param']['year'] = $year;
			$this->ses['response']['param']['month'] = $month;

			if ($month == 1) { $prevYear = $year - 1; $prevMonth = 12; } else { $prevYear = $year; $prevMonth = $month - 1; }
			if ($year == $appType['year'] && $month == $appType['month']) { $prevYear = 0; }
			$this->ses['response']['param']['prevYear'] = $prevYear;
			$this->ses['response']['param']['prevMonth'] = $prevMonth;

			if ($month == 12) { $nextYear = $year + 1; $nextMonth = 1; } else { $nextYear = $year; $nextMonth = $month + 1; }
			if ($year == $today['year'] && $month == $today['mon']) { $nextYear = 0; }
			$this->ses['response']['param']['nextYear'] = $nextYear;
			$this->ses['response']['param']['nextMonth'] = $nextMonth;

			# out
			$this->ses['response']['param']['appCount'] = count($myApps);
			$this->ses['response']['param']['app'] = $this->app;
			$this->ses['response']['param']['appName'] = $appInfo['name'];
			if ($appInfo['network'] != "") {
				$this->ses['response']['param']['appName'] = "Network: ".$appInfo['network'];
			}
			$this->ses['response']['param']['appType'] = $appType;
			$this->ses['response']['param']['statsList'] = $statsList;

		}
		
		$this->ses['response']['param']['access'] = $access;

     }

}

?>
