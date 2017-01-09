<?

class userViewFaces extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
    $this->id = $this->ses['id'];
 
	// param
	$this->limit = $this->ses['request']['param']['limit'];
	if (! isset($this->limit)) { $this->limit = 7; }

	// init
	$day= array();
	$birthDayList = array();
	$nextBirthDayList = array();
	$newList = array();
	//
	// go
	//
	list($day['day'], $day['month'], $day['year']) = explode(',', date('j,n,Y', time()));
	$birthDayList = UserApiListUserWithQuery("format=short&annotation[dateofbirthmonth]=".$day['month']."&order=birthday&limit=".$this->limit);
	if (count($birthDayList) < $this->limit ) {
		$nextMonth = $day['month'] + 1;
		if ($nextMonth == 13) { $nextMonth = 1; } 
		$nextBirthDayList = UserApiListUserWithQuery("format=short&annotation[dateofbirthmonth]=".$nextMonth."&order=birthday&limit=".$this->limit);
		$birthDayList = array_slice(array_merge($birthDayList, $nextBirthDayList), 0, $this->limit);
	}
	
	$newList = UserApiListUserWithQuery("mode=active&format=short&order=date&direction=desc&limit=".$this->limit);

	//
	// content
	//

	// full lists
	$this->ses['response']['param']['birthDayList'] = $birthDayList;
	$this->ses['response']['param']['newList'] = $newList;

	//more
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
	$this->ses['response']['param']['limit'] = $this->limit;

     }

}

?>
