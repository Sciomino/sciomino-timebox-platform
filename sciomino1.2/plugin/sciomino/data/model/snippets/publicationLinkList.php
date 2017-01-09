<?

class publicationLinkList extends control {

    function Run() {

        global $XCOW_B;
       
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->user = $this->ses['request']['param']['user'];
	if (! isset($this->user)) { $this->user = $this->userId; }

	// init
	$shareList = array();
	$blogList = array();
	$websiteList = array();
	$otherPubList = array();

	//
	// publications
	//
	// - share
	$pubShareList = array();

	$shareList =ScioMinoApiListShare($this->user);

	$shareCount = 1;
	foreach ($shareList as $share) {
		if (in_array($share['title'], $XCOW_B['rss_reader_list']) && $share['relation-self'] != '') {
			$xml = 0;
			$xmlFeed = 0;

			$response = GetResponse($XCOW_B['connect_api']['host']."connect/list?type=feed&name=".urlencode($XCOW_B['rss_reader'][$share['title']]['prefix']).urlencode($share['relation-self']).urlencode($XCOW_B['rss_reader'][$share['title']]['suffix']));

			# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
			try { $xml = new SimpleXMLElement($response); } 
			catch (Exception $ignored) { } 

			// did we get xml in the response and in the description?
			if (isset($xml) && ! empty($xml->Content->Connects->Connect->Description)) {

				$feed = (string) $xml->Content->Connects->Connect->Description;

				try { $xmlFeed = new SimpleXMLElement($feed); } 
				catch (Exception $ignored) { } 

				if (isset($xmlFeed)) {
					$pubShareList[$shareCount] = array();
					$entryCount = 1; 
					foreach ($xmlFeed->channel->item as $item) {
						$pubShareList[$shareCount][$entryCount] = array();
						$pubShareList[$shareCount][$entryCount]['header'] = $share['title'];
						$pubShareList[$shareCount][$entryCount]['title'] = (string) $item->title;
						$pubShareList[$shareCount][$entryCount]['description'] = (string) $item->description;		
						$pubShareList[$shareCount][$entryCount]['link'] = (string) $item->link;		
						$entryCount++;
						if ($entryCount > 3) {
							break;
						}
					}
					$shareCount++;
				}
			}
		}
	}

	// - blog
	$pubBlogList = array();

	$blogList =ScioMinoApiListBlog($this->user);

	$blogCount = 1;
	foreach ($blogList as $blog) {
		if (in_array($blog['title'], $XCOW_B['rss_reader_list']) && $blog['relation-other'] != '') {
			$xml = 0;
			$xmlFeed = 0;

			$response = GetResponse($XCOW_B['connect_api']['host']."connect/list?type=feed&name=".urlencode($XCOW_B['rss_reader'][$blog['title']]['prefix']).urlencode($blog['relation-other']).urlencode($XCOW_B['rss_reader'][$blog['title']]['suffix']));

			# intentionally :-), no xml from remote api, too bad... but we continu, we are on our own now...
			try { $xml = new SimpleXMLElement($response); } 
			catch (Exception $ignored) { } 

			// did we get xml in the response and in the description?
			if (isset($xml) && ! empty($xml->Content->Connects->Connect->Description)) {
				$feed = (string) $xml->Content->Connects->Connect->Description;
				
				try { $xmlFeed = new SimpleXMLElement($feed); } 
				catch (Exception $ignored) { } 

				if (isset($xmlFeed)) {
					$pubBlogList[$blogCount] = array();
					$entryCount = 1; 
					foreach ($xmlFeed->channel->item as $item) {
						$pubBlogList[$blogCount][$entryCount] = array();
						$pubBlogList[$blogCount][$entryCount]['header'] = $blog['title'];
						$pubBlogList[$blogCount][$entryCount]['blogTitle'] = (string) $xmlFeed->channel->title;
						$pubBlogList[$blogCount][$entryCount]['blogLink'] = (string) $xmlFeed->channel->link;
						$pubBlogList[$blogCount][$entryCount]['title'] = (string) $item->title;
						$pubBlogList[$blogCount][$entryCount]['description'] = (string) $item->description;		
						$pubBlogList[$blogCount][$entryCount]['link'] = (string) $item->link;		
						$entryCount++;
						if ($entryCount > 3) {
							break;
						}
					}
					$blogCount++;
				}

			}
		}
	}

	// website
	$pubWebsiteList = array();

	$websiteList = ScioMinoApiListWebsite($this->user);
	$pubWebsiteList = $websiteList;

	// otherPub
	$pubOtherPubList = array();

	$OtherPubList = ScioMinoApiListOtherPub($this->user);
	$pubOtherPubList = $OtherPubList;

	// content
	$this->ses['response']['param']['shares'] = $pubShareList;
	$this->ses['response']['param']['blogs'] = $pubBlogList;
	$this->ses['response']['param']['websites'] = $pubWebsiteList;
	$this->ses['response']['param']['otherPubs'] = $pubOtherPubList;

     }

}

?>
