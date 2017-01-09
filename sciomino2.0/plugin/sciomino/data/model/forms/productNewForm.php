<?

class productNew extends control {

    function Run() {

        global $XCOW_B;

	$this->productId = 0;
	$this->product = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

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

	$this->fillSubject = $this->ses['request']['param']['fillSubject'];
	$this->fillTitle = $this->ses['request']['param']['fillTitle'];
	$this->fillAlternative = $this->ses['request']['param']['fillAlternative'];

	$this->go = $this->ses['request']['param']['go'];
	if (! isset($this->go)) { $this->go = 0; }

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
	$missingFields = 0;
	$input = array($this->product['subject'], $this->product['title'], $this->product['alternative'], $this->product['has'], $this->product['like']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
		$missingFields = 1;
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
		//}
		//else {
		//	#
		//}

		// save
		$this->productId = ScioMinoApiSaveProduct ($this->product, $this->userId, '1'); 

		if ($this->productId != 0) {
			$this->status = "De productervaring is toegevoegd.";


		}
		else{
			$this->status = "De productervaring kon niet toegevoegd worden.";
		}

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/productNewForm2.php';
      	    	#$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/productNewFormFrame.php';
        }
        
	// show the form
        else {
			#$this->ses['response']['param']['productId'] = $this->productId;
			$this->ses['response']['param']['prevSubject'] = $this->product['subject'];
			$this->ses['response']['param']['prevTitle'] = $this->product['title'];
			$this->ses['response']['param']['prevAlternative'] = $this->product['alternative'];
			$this->ses['response']['param']['fillSubject'] = $this->fillSubject;
			$this->ses['response']['param']['fillTitle'] = $this->fillTitle;
			$this->ses['response']['param']['fillAlternative'] = $this->fillAlternative;
			$this->ses['response']['param']['go'] = $this->go;
			$this->ses['response']['param']['missing'] = $missingFields;
       }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
