<?

class wizardPhoto extends control {

    function Run() {

        global $XCOW_B;

        $this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);
		$this->annotationId = 0;

		$this->go = $this->ses['request']['param']['go'];
		$this->photo = $this->ses['request']['param']['photo'];

		// local additions
		$this->userList = array();
		$this->user = array();
		$this->annotation = array();

		// image init
		$this->file_tmp = $this->ses['request']['file_info']['file']['tmp_name'];
		$this->file_name = basename($this->ses['request']['file_info']['file']['name']);
		$this->file_size = $this->ses['request']['file_info']['file']['size'];

		$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
		$this->file_id = md5($this->file_size.microtime().date("r").mt_rand(11111, 99999));
		$this->file_upload_name = $this->file_id.".".$this->file_ext;
		$this->file_upload_location = $XCOW_B['upload_base']."/".$this->file_upload_name;

		//
		// check fields?
		//
		$input = array();
		if (! noEmptyInput($input) ) {
			$status = language("session_status_register_requiredfield");
		}
	
        //
        // if the fields are checked, update the user info
        // otherwise proceed to the view and show a form where new info can be entered
        //
        if (! $this->status && $this->go) {

			// upload image to 'file_name_new'
			$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

			// if upload OK 
			// => move image to 'destination'
			// => update image info
			if ($uploadStatus['status'] == 1) {
				$this->status = language('sciomio_text_wizard_photo_status_ok');

      			moveUploadFile ($this->file_upload_location, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
				
				$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;
				$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);

				// create thumbnails
				createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 96, 96);
				createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 48, 48);
				createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 32, 32);

				// reset original photo size
				if ($XCOW_B['sciomino']['original-photo-size'] != 0) {
					createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, $XCOW_B['sciomino']['original-photo-size'], $XCOW_B['sciomino']['original-photo-size']);
           			moveUploadFile ($XCOW_B['upload_destination_dir']."/".$XCOW_B['sciomino']['original-photo-size']."x".$XCOW_B['sciomino']['original-photo-size']."_".$this->file_upload_name, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
				}

				// verwijder oude file
				if ($this->photo != "") {
					unlink($XCOW_B['upload_destination_dir']."/".basename($this->photo));
					unlink($XCOW_B['upload_destination_dir']."/96x96_".basename($this->photo));
					unlink($XCOW_B['upload_destination_dir']."/48x48_".basename($this->photo));
					unlink($XCOW_B['upload_destination_dir']."/32x32_".basename($this->photo));
				}
				
				$reload = 1;
			}

			$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/import-wizard/4-photoFrame.php';
			$this->ses['response']['param']['reload'] = $reload;

        }
	    // attempt failed, try again
	    else {
			$userList = current(UserApiListUserByReference($this->id));
			$this->ses['response']['param']['user'] = $userList;
        }
		
        $this->ses['response']['param']['status'] = $this->status;

	}

}

?>
