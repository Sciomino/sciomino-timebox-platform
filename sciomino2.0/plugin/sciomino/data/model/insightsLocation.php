<?

class insightsLocation extends control {

    function Run() {

        global $XCOW_B;

	$statsList = array();
	$locationStats = array();
     
        $id = $this->ses['id'];

	// read input
        $this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'workplace';}

	// get stats
	$statsList = UserApiListStats("SC_UserApiListStats");
	$stats = current($statsList);

	if ($this->mode == "workplace") {
		$locationStats['CityCount'] = $stats['WorkplaceCount'];
		$locationStats['CountryCount'] = $stats['WorkplaceCountryCount'];
		$locationStats['Cities'] = $stats['UserCountXWorkplace'];
		$locationStats['Countries'] = $stats['WorkplaceCountXCountry'];
	}
	else {
		$locationStats['CityCount'] = $stats['HometownCount'];
		$locationStats['CountryCount'] = $stats['HometownCountryCount'];
		$locationStats['Cities'] = $stats['UserCountXHometown'];
		$locationStats['Countries'] = $stats['HometownCountXCountry'];
	}

	// sort functions
	function sortStats($x, $y) {
		if ( $x['count'] == $y['count'] ) { return 0; }
		elseif ( $x['count'] < $y['count'] ) { return -1; }
		else { return 1; }
	}
	function rsortStats($x, $y) {
		if ( $x['count'] == $y['count'] ) { return 0; }
		elseif ( $x['count'] > $y['count'] ) { return -1; }
		else { return 1; }
	}

	// create sorted lists
	if ( get_id_from_multi_array($locationStats['Cities'], 'label', 'UNKNOWN') != 0 ) {
		unset($locationStats['Cities'][get_id_from_multi_array($locationStats['Cities'], 'label', 'UNKNOWN')]);
		$locationStats['CityCount'] = $locationStats['CityCount'] - 1;
	}
	uasort($locationStats['Cities'], "rsortStats");
	$locationStats['TopCity'] = array_slice($locationStats['Cities'], 0, 3, true);
	$locationStats['BottomCity'] = array_slice($locationStats['Cities'], -3, 3, true);
	uasort($locationStats['BottomCity'], "sortStats");

	if ( get_id_from_multi_array($locationStats['Countries'], 'label', 'UNKNOWN') != 0 ) {
		unset($locationStats['Countries'][get_id_from_multi_array($locationStats['Countries'], 'label', 'UNKNOWN')]);
		$locationStats['CountryCount'] = $locationStats['CountryCount'] - 1;	
	}
	uasort($locationStats['Countries'], "rsortStats");
	$locationStats['TopCountry'] = array_slice($locationStats['Countries'], 0, 1, true);
	$locationStats['BottomCountry'] = array_slice($locationStats['Countries'], -1, 1, true);
	uasort($locationStats['BottomCountry'], "sortStats");

	// content
	$this->ses['response']['param']['locations'] = $locationStats;
	$this->ses['response']['param']['mode'] = $this->mode;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

	
     }

}

?>
