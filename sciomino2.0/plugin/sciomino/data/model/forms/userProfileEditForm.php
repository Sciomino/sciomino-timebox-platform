<?

class userProfile extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = 0;

	$this->annotationId = 0;
	$this->contactId = 0;
	$this->addressId = 0;
	$this->organizationId = 0;

	$reload = 0;

	// init
	$this->userList = array();
	$this->contactList = array();
	$this->addressList = array();
	$this->organizationList = array();

	$this->user = array();
	$this->annotation = array();
	$this->contact = array();
	$this->contact['Home'] = array();
	$this->contact['Work'] = array();
	$this->address = array();
	$this->address['Home'] = array();
	$this->address['Work'] = array();
	$this->organization = array();
	$this->organization['Current'] = array();
	$this->organization['Past'] = array();

	$this->go = $this->ses['request']['param']['go'];
	$this->photo = $this->ses['request']['param']['photo'];

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
		$this->annotation['dateofbirthshow'] = $this->ses['request']['param']['dateofbirthshow'];
		if (! isset($this->annotation['dateofbirthshow'])) { $this->annotation['dateofbirthshow'] = 0; }
	}
	if (! in_array("gender", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->annotation['gender'] = $this->ses['request']['param']['gender'];
	}

	// other personal information is always editable
	$this->annotation['description'] = $this->ses['request']['param']['description'];

	$this->contact['Home']['email'] = $this->ses['request']['param']['email'];
	$this->contact['Home']['telHome'] = $this->ses['request']['param']['telHome'];
	$this->contact['Home']['telMobile'] = $this->ses['request']['param']['telMobile'];

	$this->address['Home']['address'] = $this->ses['request']['param']['address'];
	$this->address['Home']['postalcode'] = $this->ses['request']['param']['postalcode'];
	$this->address['Home']['city'] = $this->ses['request']['param']['city'];
	$this->address['Home']['country'] = $this->ses['request']['param']['country'];

	// work information not editable if using a remote source for profile information	
	// - work: industry, company, building, room, role, division, section, parttime
	// - work contact: email, telIntern, telExtern, mobile, lync, pager, fax, pac, myId, assistentId, managerId
	// - work address: address, postalcode, city, country
	if (! in_array("industry", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['industry'] = $this->ses['request']['param']['industry'];
	}
	if (! in_array("company", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['company'] = $this->ses['request']['param']['company'];
	}
	if (! in_array("building", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['building'] = $this->ses['request']['param']['building'];
	}
	if (! in_array("room", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['room'] = $this->ses['request']['param']['room'];
	}
	if (! in_array("role", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['role'] = $this->ses['request']['param']['role'];
	}
	if (! in_array("division", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['division'] = $this->ses['request']['param']['division'];
	}
	if (! in_array("section", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['section'] = $this->ses['request']['param']['section'];
	}
	if (! in_array("parttime", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->organization['Current']['parttime'] = $this->ses['request']['param']['parttime'];
	}
		#$this->organization['Current']['startDate'] = $this->ses['request']['param']['startDate'];
		#$this->organization['Current']['endDate'] = $this->ses['request']['param']['endDate'];

	if (! in_array("email", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['email'] = $this->ses['request']['param']['emailWork'];
	}
	if (! in_array("telIntern", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['telIntern'] = $this->ses['request']['param']['telInternWork'];
	}
	if (! in_array("telExtern", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['telExtern'] = $this->ses['request']['param']['telExternWork'];
	}
	if (! in_array("mobile", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['telMobile'] = $this->ses['request']['param']['telMobileWork'];
	}
	if (! in_array("lync", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['telLync'] = $this->ses['request']['param']['telLyncWork'];
	}
	if (! in_array("pager", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['telPager'] = $this->ses['request']['param']['telPagerWork'];
	}
	if (! in_array("fax", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['telFax'] = $this->ses['request']['param']['telFaxWork'];
	}
	if (! in_array("pac", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['pac'] = $this->ses['request']['param']['pac'];
	}
	if (! in_array("myId", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['myId'] = $this->ses['request']['param']['myId'];
	}
	if (! in_array("assistentId", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['assistentId'] = $this->ses['request']['param']['assistentId'];
	}
	if (! in_array("managerId", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->contact['Work']['managerId'] = $this->ses['request']['param']['managerId'];
	}

	if (! in_array("address", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->address['Work']['address'] = $this->ses['request']['param']['addressWork'];
	}
	if (! in_array("postalcode", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->address['Work']['postalcode'] = $this->ses['request']['param']['postalcodeWork'];
	}
	if (! in_array("city", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->address['Work']['city'] = $this->ses['request']['param']['cityWork'];
	}
	if (! in_array("country", $XCOW_B['sciomino']['personalia-filled']) ) {
		$this->address['Work']['country'] = $this->ses['request']['param']['countryWork'];
	}

	// image init
	//print_r($this->ses['request']);
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
		$this->status = "Input Error";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where the product is shown
        //
        if (! $this->status && $this->go) {

		// update
		if (($this->userId = UserApiUpdateUserByReference($this->user, $this->id)) != 0) {
			$this->status = language('sciomio_text_user_profile_edit_status_ok');

			if ($XCOW_B['sciomino']['personalia-view'] == 'local') {
				updateDisplayName($this->id, $this->user['firstName']." ".$this->user['lastName']);
			}

			$this->contactId = ScioMinoApiUpdateContact($this->contact, $this->userId);
			$this->addressId = ScioMinoApiUpdateAddress($this->address, $this->userId);
			$this->organizationId = ScioMinoApiUpdateOrganization($this->organization, $this->userId);

			// upload image to 'file_name_new'
			$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

			// if upload OK 
			// => move image to 'destination'
			// => update image info
			if ($uploadStatus['status'] == 1) {
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
			else {
				# $this->status .= " (Maar de upload van het plaatje is niet geslaagd: ".$uploadStatus['message'].")";
				$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);

			}

		}
		else{
			$this->status = language('sciomio_text_user_profile_edit_status_wrong');
		}

      	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/userProfileEditFormFrame.php';
		$this->ses['response']['param']['reload'] = $reload;
        }
        
	// show the form
        else {

		# get user profile
		$name = getUserDisplayNameFromUserId($this->id);
		if ($name == '') { $name = getUserNameFromUserId($this->id);}
		$this->ses['response']['param']['displayName'] = $name;

		# maybe could use format=long?
		// this call is cached by default in UserApiClient
		$userList = current(UserApiListUserByReference($this->id));
		$this->ses['response']['param']['user'] = $userList;

		$contactList = ScioMinoApiListContact($userList['Id']);
		$this->ses['response']['param']['contact'] = $contactList;

		$addressList = ScioMinoApiListAddress($userList['Id']);
		$this->ses['response']['param']['address'] = $addressList;

		$organizationList = ScioMinoApiListOrganization($userList['Id']);
		$this->ses['response']['param']['organization'] = $organizationList;

		# different views depending on local or remote profile infomration
		if ($XCOW_B['sciomino']['personalia-view'] == 'local') {
	      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/userProfileEditFormLocal.php';
		}

        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
