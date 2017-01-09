<?

class availabilityStatus extends control {

    function Run() {

        global $XCOW_B;
        
        $this->id = $this->ses['id'];
        $this->app = $this->ses['request']['param']['app'];

		# update data
        $this->update = $this->ses['request']['param']['update'];
        if (! isset($this->update)) { $this->update = 0; }

		# which week?
        $this->weekOffset = $this->ses['request']['param']['weekOffset'];
        if (! isset($this->weekOffset)) { $this->weekOffset = 0; }
       
		# test access
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

			# data
			$availabilityData = GraphDataFetch($appTypeId, $this->app, $appInfo, $this->update);
			$availabilityDataTimestamp = GraphDataFetchTimestamp($appTypeId, $this->app);
				
			# calendar
			
			// calculate day offset
			$todayDate = getdate(time());
			$todayTimestamp = $todayDate[0];
			$currentDate = getdate(time() + ($this->weekOffset *7*24*60*60));
			$currentTimestamp = $currentDate[0];
			$currentWeekDay = $currentDate['wday'];
			$currentDay = $currentDate['mday'];
			
			// calculate monday
			// - week starts with sunday/0
			$currentWeekDayOffset = 1;
			if ($currentWeekDay == 0) { $currentWeekDayOffset = -6; }
			$currentMonday = getdate(time() + ($this->weekOffset *7*24*60*60) - ( ($currentWeekDay - $currentWeekDayOffset) *24*60*60) );
			$currentMondayTimestamp = $currentMonday[0];
			$currentMondayDay = $currentMonday['mday'];
			$currentMondayMonth = $currentMonday['mon'];

			// calculate some stuff
			$weekNr = date('W', $currentMondayTimestamp);
			$lastDay = date('t', $currentMondayTimestamp);

			// save daynumbers
			$dayNumbers = array();
			$count = $currentMondayDay;
			$count2 = $currentMondayMonth;
			for ($i=1;$i<=7;$i++) {
				$dayNumbers[$i] = $count."/".$count2;
				
				if ($lastDay == 0) {
					$count++;
				}
				else {
					if ($count < $lastDay) {
						$count++;
					}
					else {
						$lastDay = 0;
						$count = 1;
						$count2 = $count2 + 1;
						if ($count2 > 12) {$count2 = 1;}
					}
				}
			}
			
			// 6 months/26 weeks ahead
			$nextWeeks = array();
			$firstMonday = getdate($currentMondayTimestamp - ($this->weekOffset *7*24*60*60) );
			$firstMondayTimestamp = $firstMonday[0];
			for ($i=0;$i<26;$i++) {
				$nextMonday = getDate($firstMondayTimestamp + ($i *7*24*60*60));
				$nextWeeks[$i] = array();
				$nextWeeks[$i]['offset'] = $i;
				$nextWeeks[$i]['week'] = date('W', $nextMonday[0]);
				$nextWeeks[$i]['day'] = $nextMonday['mday'];
				$nextWeeks[$i]['month'] = $nextMonday['mon'];
			}

			# out
			$this->ses['response']['param']['appCount'] = count($myApps);
			$this->ses['response']['param']['app'] = $this->app;
			$this->ses['response']['param']['appName'] = $appInfo['name'];
			if ($appInfo['network'] != "") {
				//$this->ses['response']['param']['appName'] = "Network: ".$appInfo['network'];
				$this->ses['response']['param']['appName'] = $appInfo['network'];
			}
			$this->ses['response']['param']['appType'] = $appType;
			
			# data
			$this->ses['response']['param']['availabilityData'] = $availabilityData;
			
			# calendar
			$this->ses['response']['param']['today'] = $todayTimestamp;
			$this->ses['response']['param']['calendarWeekoffset'] = $this->weekOffset;
			$this->ses['response']['param']['calendarWeek'] = $weekNr;
			$this->ses['response']['param']['calendarDay'] = $dayNumbers;
			$this->ses['response']['param']['calendarMonday'] = $currentMondayTimestamp;
			$this->ses['response']['param']['calendarNext'] = $nextWeeks;
			
			$data = json_decode($availabilityData);
			$this->ses['response']['param']['userCount'] = $data->content->summary->completeListSize;

			$this->ses['response']['param']['availabilityDataTimestamp'] = $availabilityDataTimestamp;
			#print_r(json_decode($availabilityData));
			
		}
		
		$this->ses['response']['param']['access'] = $access;

     }

}

?>
