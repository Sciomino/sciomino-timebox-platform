<?

class insightsBirthday extends control {

    function Run() {

        global $XCOW_B;

	$timestamp = 0;
	$day = array();
	$userList = array();
        
        $id = $this->ses['id'];

	// read input
        $this->day = $this->ses['request']['param']['day'];
        if (! isset($this->day)) {$this->day = 'today';}

	// go
	if ($this->day == "today") {
		$timestamp = time();
	}
	elseif ($this->day == "tomorrow") {
		$timestamp = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
	}
	else {
		$timestamp = strtotime($this->day);
	}

	list($day['day'], $day['month'], $day['year']) = explode(',', date('j,n,Y', $timestamp));

	$userString = "";
	$userString .= "annotation[dateofbirthday]=".$day['day'];
	$userString .= "&annotation[dateofbirthmonth]=".$day['month'];
	# it's optional to show/hide users from the daily calendar
	# 1. if the calendar option is excluded, everyone is shown
	# 2. if the calendar option is not excluded, the following rules apply:
	# - default value = "" or 0, everyone is hidden from the calendar
	# - a value of 1 is explicitely set by the user
	if (! in_array("calendar", $XCOW_B['sciomino']['personalia-exclude']) ) {
		$userString .= "&annotation[dateofbirthshow]=1";
	}
	$userList = UserApiListUserWithQuery($userString, "SC_UserApiListUserWithQuery_".$day['day']."_".$day['month']."_date");

	// content
	$this->ses['response']['param']['day'] = $today;
	$this->ses['response']['param']['userList'] = $userList;
	
     }

}

?>
