<?

class externalWikipedia extends control {

    function Run() {

        global $XCOW_B;

		//
		// who?
		//
		$this->id = $this->ses['id'];

		$this->knowledge = $this->ses['request']['param']['k'];

		// external content
		$externalContent = '';
		$externalContentUrl = '';
		$connectLanguage = "";
		if ($this->ses['response']['language'] != "nl") {
			$connectLanguage = "-".$this->ses['response']['language'];
		}
		$response = GetResponse($XCOW_B['connect_api']['host'].$XCOW_B['sciomino']['connect-wiki'].$connectLanguage."/view/".urlencode(str_replace(' ', '_', $this->knowledge)));

		# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
		try { $xml = new SimpleXMLElement($response); } 
		catch (Exception $ignored) { } 

		// did we get xml in the response and in the description?
		if (isset($xml) && ! empty($xml->Content->Connects->Connect->Description)) {
			$externalContent = (string) $xml->Content->Connects->Connect->Description;
			$externalContentUrl = (string) $xml->Content->Connects->Connect->Url;
		}
		else {
			$externalContent = "niet gevonden";
		}

		// content
		$this->ses['response']['param']['externalContent'] = $externalContent;
		$this->ses['response']['param']['externalContentUrl'] = $externalContentUrl;

		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
	}
	
}

?>
