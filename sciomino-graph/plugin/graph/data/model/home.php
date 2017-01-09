<?

class webHome extends control {

    function Run() {

        global $XCOW_B;
        
        $this->id = $this->ses['id'];
        
        $myApps = GraphSessionAppList($this->id);
        
        foreach ($myApps as $key => $val) {
			$appInfo = array();
			$appInfo = GraphSessionGetAppInfo($val['id']);
			$appType = array();
			$appType = GraphSessionGetAppType($val['typeId']);
			$myApps[$key] = array_merge($myApps[$key], $appInfo, $appType);
		}

		$this->ses['response']['param']['appList'] = $myApps;
		$this->ses['response']['param']['appCount'] = count($myApps);
		
		if (count($myApps) == 1) {
			$app = current($myApps);
			$this->ses['response']['redirect'] = "/".$app['typeName']."/status?app=".$app['id'];
		}
		
     }

}

?>
