<?

class externalEvent extends control {

    function Run() {

        global $XCOW_B;

		//
		// who?
		//
		$this->id = $this->ses['id'];
		$this->tag = $this->ses['request']['param']['t'];

		// external content
		$externalContent = 0;
		$externalContentTitle = '';
		$externalContentDescription = '';
		$externalContentLink = '';
		$externalContentImage = '';
		$response = GetResponse($XCOW_B['connect_api']['host']."/connect/list?reference=".$XCOW_B['user_api']['id']."&type=event&format=long&name_match=exact&name=".urlencode($this->tag));
		# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
		try { $xml = new SimpleXMLElement($response); } 
		catch (Exception $ignored) {} 

		// did we get xml in the response and in the description?
		if (isset($xml) && ! empty($xml->Content->Connects->Connect->Title)) {
			$externalContent = 1;
			$externalContentTitle = (string) $xml->Content->Connects->Connect->Title;
			$externalContentDescription = (string) $xml->Content->Connects->Connect->Description;
			$externalContentLink = (string) $xml->Content->Connects->Connect->Link;
			$externalContentImage = (string) $xml->Content->Connects->Connect->Image;
		}

		// content
		$this->ses['response']['param']['externalContent'] = $externalContent;
		$this->ses['response']['param']['externalContentTitle'] = $externalContentTitle;
		$this->ses['response']['param']['externalContentDescription'] = $externalContentDescription;
		$this->ses['response']['param']['externalContentLink'] = $externalContentLink;
		$this->ses['response']['param']['externalContentImage'] = $externalContentImage;

		$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];
	}
	
}

?>
