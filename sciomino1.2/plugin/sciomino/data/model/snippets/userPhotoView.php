<?php

class userPhotoView extends control {

    function Run() {

        global $XCOW_B;

		// who?
        $this->id = $this->ses['id'];

		// params
		$this->userRef = $this->ses['request']['param']['userRef'];

		// get photo by userRef (uses the cache)
		$userInfo = current(UserApiListUserWithQuery("reference=".$this->userRef."&format=short", "SC_UserApiListUserWithQuery_".$this->userRef."_short"));

		$imageInfo = array();
		$imageInfo = getimagesize($XCOW_B['upload_destination_dir']."/../".$userInfo['photo']);
		
		$width=1;
		$height=1;
		$width = $imageInfo[0];
		$height = $imageInfo[1];
		
		$maxWidth = 800;
		$maxHeight = 600;
		if( $width > $maxWidth) {
			$ratio = $maxWidth/$width;
			$width = $maxWidth;
			$height = round($height * $ratio);
		}
		if( $height > $maxHeight) {
			$ratio = $maxHeight/$height;
			$height = $maxHeight;
			$width = round($width * $ratio);
		}
		
		// content
		$this->ses['response']['param']['photo'] = $userInfo['photo'];
		$this->ses['response']['param']['width'] = $width;
		$this->ses['response']['param']['height'] = $height;

	}

}

?>
