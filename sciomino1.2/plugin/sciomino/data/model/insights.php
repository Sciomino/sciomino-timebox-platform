<?

class insights extends control {

    function Run() {

        global $XCOW_B;

	$statsList = array();

	$today = array();
	$userList = array();
        
        $id = $this->ses['id'];

	// get stats
	$statsList = UserApiListStats("SC_UserApiListStats");
	$statsList = current($statsList);
#print_r($statsList);

	// filter UNKNOWN
        if ( get_id_from_multi_array($statsList['UserCountXWorkplace'], 'label', 'UNKNOWN') != 0 ) {
                $statsList['WorkplaceCount'] = $statsList['WorkplaceCount'] - 1;
        }
        if ( get_id_from_multi_array($statsList['WorkplaceCountXCountry'], 'label', 'UNKNOWN') != 0 ) {
                $statsList['WorkplaceCountryCount'] = $statsList['WorkplaceCountryCount'] - 1;
        }
        if ( get_id_from_multi_array($statsList['UserCountXHometown'], 'label', 'UNKNOWN') != 0 ) {
                $statsList['HometownCount'] = $statsList['HometownCount'] - 1;
        }
        if ( get_id_from_multi_array($statsList['HometownCountXCountry'], 'label', 'UNKNOWN') != 0 ) {
                $statsList['HometownCountryCount'] = $statsList['HometownCountryCount'] - 1;
        }

	list($today['day'], $today['month'], $today['year']) = explode(',', date('j,n,Y'));
	$userString = "";
	$userString .= "annotation[dateofbirthday]=".$today['day'];
	$userString .= "&annotation[dateofbirthmonth]=".$today['month'];
	# it's optional to show/hide users from the daily calendar
	# 1. if the calendar option is excluded, everyone is shown
	# 2. if the calendar option is not excluded, the following rules apply:
	# - default value = "" or 0, everyone is hidden from the calendar
	# - a value of 1 is explicitely set by the user
	if (! in_array("calendar", $XCOW_B['sciomino']['personalia-exclude']) ) {
		$userString .= "&annotation[dateofbirthshow]=1";
	}
	$userList = UserApiListUserWithQuery($userString, "SC_UserApiListUserWithQuery_".$today['day']."_".$today['month']."_date");

	// content
	$this->ses['response']['param']['stats'] = $statsList;

	$this->ses['response']['param']['today'] = $today;
	$this->ses['response']['param']['userList'] = $userList;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

	
     }

}

?>
