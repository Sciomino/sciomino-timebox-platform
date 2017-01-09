<?

class actCloseNew extends control {

    function Run() {

        global $XCOW_B;

	$actId = 0;
	$this->act = array();
	$this->annotation = array();

	// who?
        $this->id = $this->ses['id'];

	// params
	$this->parent = $this->ses['request']['param']['act'];

	$this->act['parent'] = $this->ses['request']['param']['com_act'];
	$this->act['description'] = $this->ses['request']['param']['com_description'];

	// like
	$this->annotation['like'] = $this->ses['request']['param']['com_like'];
	$this->annotation['story'] = 1;

	// photo
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
	$input = array($this->act['parent'], $this->act['description'], $this->annotation['like']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save, BEWARE save with id, NOT with userId
		$actId = AnswersApiSaveAct ($this->act, $this->id, '1'); 

		if ($actId != 0) {
			$this->status = language('sciomio_text_act_close_new_status_ok');
		}
		else{
			$this->status = language('sciomio_text_act_close_new_status_wrong');
		}

		// upload image to 'file_name_new'
		$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

		// if upload OK 
		// => move image to 'destination'
		// => update image info
		if ($uploadStatus['status'] == 1) {
    			moveUploadFile ($this->file_upload_location, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
			
			$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;

			// verwijder oude file
			//if ($this->photo != "") {
			//	unlink($XCOW_B['upload_destination_dir']."/".basename($this->photo));
			//}
			
			$reload = 1;
		}
		// save anno
		$this->annotationId = AnswersApiSaveActAnnotationList($this->annotation, $actId, 1);

		// expire parent (if necessary)
		$parentId = $this->act['parent'];
		$parentList = AnswersApiListActById($parentId);
		if (($parentList[$parentId]['Timestamp'] + $parentList[$parentId]['Expiration']) > time()) {
			#not yet expired
			$newAct = array();
			$newAct['expiration'] = time() - $parentList[$parentId]['Timestamp'];
			AnswersApiUpdateAct ($this->act['parent'], $newAct);
		}

      	    	//$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/actCloseNewForm2.php';
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/actCloseNewFormFrame.php';
		$this->ses['response']['param']['url'] = $XCOW_B['url']."/act/view?act=".$this->act['parent'];
        }
        
	// show the form
        else {
		#$this->ses['response']['param']['productId'] = $this->productId;
	       $this->ses['response']['param']['parent'] = $this->parent;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
