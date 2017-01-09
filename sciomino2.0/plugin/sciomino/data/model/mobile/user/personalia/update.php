<?

class userPersonaliaUpdate extends control {

    function Run() {

        global $XCOW_B;

		//
		// who?
		//
        $this->id = $this->ses['id'];
		$this->userId = 0;

		$this->annotationId = 0;

		// init
		$this->user = array();
		$this->annotation = array();
		$status = 0;
		$message = "";

		// params
	
		// personal information not editable if using a remote source for profile information	
		// - personal: title, firstname, lastname, dateofbirth, gender
		if (! in_array("title", $XCOW_B['sciomino']['personalia-filled']) ) {
			$this->annotation['title'] = $this->ses['request']['param']['title'];
		}
		if (! in_array("firstname", $XCOW_B['sciomino']['personalia-filled']) ) {
			$this->user['firstName'] = $this->ses['request']['param']['firstName'];
		}
		if (! in_array("lastname", $XCOW_B['sciomino']['personalia-filled']) ) {
			$this->user['lastName'] = $this->ses['request']['param']['lastName'];
		}
		if (! in_array("dateofbirth", $XCOW_B['sciomino']['personalia-filled']) ) {
			$this->annotation['dateofbirthday'] = $this->ses['request']['param']['dateofbirthday'];
			$this->annotation['dateofbirthmonth'] = $this->ses['request']['param']['dateofbirthmonth'];
			$this->annotation['dateofbirthyear'] = $this->ses['request']['param']['dateofbirthyear'];
			// hum, this is default voor sciomino.com, but might not be for businesses
			$this->annotation['dateofbirthshow'] = 1;
		}
		if (! in_array("gender", $XCOW_B['sciomino']['personalia-filled']) ) {
			$this->annotation['gender'] = $this->ses['request']['param']['gender'];
		}

		// image init
		$this->photo = $this->ses['request']['param']['photo'];
		$this->photoStream = $this->ses['request']['param']['photoStream'];

		$this->file_name = "";
		if (trim($this->photo) != '') {
			$this->file_name = $this->photo;
			$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
			$this->file_id = md5(microtime().date("r").mt_rand(11111, 99999));
			$this->file_upload_name = $this->file_id.".".$this->file_ext;
		}
	
		// update
		if (($this->userId = UserApiUpdateUserByReference($this->user, $this->id)) != 0) {
			$status = 1;
			$message = language('sciomio_text_user_profile_edit_status_ok');

			//set displayname
			if ($XCOW_B['sciomino']['personalia-view'] == 'local') {
				updateDisplayName($this->id, $this->user['firstName']." ".$this->user['lastName']);
			}

			// photo
			if ($this->file_name != '') {
				if (trim($this->photoStream) != '') {
					file_put_contents($XCOW_B['upload_destination_dir']."/".$this->file_upload_name, base64_decode(trim($this->photoStream)));

					// thumbnails
					createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 96, 96);
					createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 48, 48);
					createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 32, 32);

					// reset original photo size
					if ($XCOW_B['sciomino']['original-photo-size'] != 0) {
						createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, $XCOW_B['sciomino']['original-photo-size'], $XCOW_B['sciomino']['original-photo-size']);
						moveUploadFile ($XCOW_B['upload_destination_dir']."/".$XCOW_B['sciomino']['original-photo-size']."x".$XCOW_B['sciomino']['original-photo-size']."_".$this->file_upload_name, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
					}

					// todo: remove original...
					
					// set photo annotation
					$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;
				}
			}

			// annotation update
			$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);

		}
		else{
			$message = language('sciomio_text_user_profile_edit_status_wrong');
		}

        $this->ses['response']['param']['status'] = $status;
        $this->ses['response']['param']['message'] = $message;

		
		// allow resource, for testing only!!!
		$this->ses['response']['header'] = "Access-Control-Allow-Origin:*"; 

    }

    function GetHeader() {
        #$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
        return ($this->ses['response']['header']);
    }

}

?>
