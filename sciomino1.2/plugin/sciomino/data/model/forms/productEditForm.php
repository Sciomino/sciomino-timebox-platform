<?

class productEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->product = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->productId = makeIntString($this->ses['request']['param']['productId']);
	//$this->relationImage = $this->ses['request']['param']['relationImage'];

	$this->product['subject'] = $this->ses['request']['param']['com_subject'];
	$this->product['title'] = $this->ses['request']['param']['com_title'];
	$this->product['alternative'] = $this->ses['request']['param']['alternative'];
	$this->product['has'] = $this->ses['request']['param']['com_has'];
	$this->product['like'] = $this->ses['request']['param']['com_like'];
	$this->product['positive1'] = $this->ses['request']['param']['positive1'];
	$this->product['positive2'] = $this->ses['request']['param']['positive2'];
	$this->product['positive3'] = $this->ses['request']['param']['positive3'];
	$this->product['negative1'] = $this->ses['request']['param']['negative1'];
	$this->product['negative2'] = $this->ses['request']['param']['negative2'];
	$this->product['negative3'] = $this->ses['request']['param']['negative3'];

	// image init
	//$this->file_tmp = $this->ses['request']['file_info']['file']['tmp_name'];
	//$this->file_name = basename($this->ses['request']['file_info']['file']['name']);
	//$this->file_size = $this->ses['request']['file_info']['file']['size'];

	//$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
	//$this->file_id = md5($this->file_size.microtime().date("r").mt_rand(11111, 99999));
	//$this->file_upload_name = $this->file_id.".".$this->file_ext;
	//$this->file_upload_location = $XCOW_B['upload_base']."/".$this->file_upload_name;

	//
	// check fields?
	//
	$input = array($this->product['subject'], $this->product['title'], $this->product['alternative'], $this->product['has'], $this->product['like']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

	// allow update?
	if (! array_key_exists($this->productId, ScioMinoApiGetProduct($this->userId, $this->productId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// upload image to 'file_name_new'
		//$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

		// if upload OK 
		// => move image to 'destination'
		// => add image info to product
		//if ($uploadStatus['status'] == 1) {
    		//	moveUploadFile ($this->file_upload_location, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
		//	
		//	$this->product['relation-image'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;
		//
		//	// verwijder oude file
		//	if ($this->relationImage != "") {
		//		unlink($XCOW_B['upload_destination_dir']."/".basename($this->relationImage));
		//	}
		//}
		//else {
		//	#
		//}

		// save
		$productId = ScioMinoApiUpdateProduct($this->product, $this->userId, $this->productId);

		if ($productId != 0) {
			$this->status = "De productervaring is bewerkt.";
		}
		else{
			$this->status = "De productervaring kon niet bewerkt worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/productEditForm2.php';
      	    	#$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/productEditFormFrame.php';
        }
        
	// show the form
        else {
		$this->product = ScioMinoApiGetProduct($this->userId, $this->productId);
		$this->ses['response']['param']['product'] = $this->product[$this->productId];
		$this->ses['response']['param']['productId'] = $this->productId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
