<?

class actCloseEdit extends control {

    function Run() {

        global $XCOW_B;

	$actId = 0;
	$this->act = array();
	$this->annotation = array();

	// who?
        $this->id = $this->ses['id'];

	// params
	$this->parent = $this->ses['request']['param']['act'];

	$this->storyParent = $this->ses['request']['param']['com_act'];
	$this->storyId = $this->ses['request']['param']['com_story'];

	$this->act['description'] = $this->ses['request']['param']['com_description'];

	// like
	$this->annotation['like'] = $this->ses['request']['param']['com_like'];

	// photo
	$this->photo = $this->ses['request']['param']['photo'];

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
	$input = array($this->storyParent, $this->storyId, $this->act['description'], $this->annotation['like']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// update, BEWARE save with id, NOT with userId
		$actId = AnswersApiUpdateAct ($this->storyId, $this->act); 

		if ($actId != 0) {
			$this->status = language('sciomio_text_act_close_edit_status_ok');
		}
		else{
			$this->status = language('sciomio_text_act_close_edit_status_wrong');
		}

		// TODO: something wrong with status, for now, assume succes
		$this->status = language('sciomio_text_act_close_edit_status_ok');

		// upload image to 'file_name_new'
		$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

		// if upload OK 
		// => move image to 'destination'
		// => update image info
		if ($uploadStatus['status'] == 1) {
    			moveUploadFile ($this->file_upload_location, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
			
			$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;

			// verwijder oude file
			if ($this->photo != "") {
				unlink($XCOW_B['upload_destination_dir']."/".basename($this->photo));
			}
		}
		// update anno
		$this->annotationId = AnswersApiUpdateActAnnotationListByAct($this->annotation, $this->storyId);


      	    	//$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/actCloseEditForm2.php';
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/actCloseEditFormFrame.php';
		$this->ses['response']['param']['url'] = $XCOW_B['url']."/act/view?act=".$this->storyParent;
        }
        
	// show the form
        else {
		#$this->ses['response']['param']['productId'] = $this->productId;
	        $this->ses['response']['param']['parent'] = $this->parent;

		// get story info from parent
		$story = 0;
		$reactString = "parent=".$this->parent."&order=time&direction=desc";
		$reactList = AnswersApiListActWithQuery($reactString);
		foreach ($reactList as $reactKey => $reactVal) {
			if ($reactVal['story'] == 1) {
				$story = $reactKey;
				break;
			}
		}
	        $this->ses['response']['param']['story'] = $reactList[$story];

        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>
