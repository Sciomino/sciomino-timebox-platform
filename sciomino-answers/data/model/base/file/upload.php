<?

class fileUpload extends control {

    //
    // NOTE: the maximum_execution_time in the php.ini file may interrupt this file, no status can be given to the user.
    //
    // $this->ses['request']['file_info']['file']['name'] The original name of the file on the client machine. 
    // $this->ses['request']['file_info']['file']['type'] The mime type of the file
    // $this->ses['request']['file_info']['file']['size'] The size, in bytes, of the uploaded file. 
    // $this->ses['request']['file_info']['file']['tmp_name'] The temporary filename of the file in which the uploaded file was stored on the server. 
    // $this->ses['request']['file_info']['file']['error'] The error code associated with this upload.
    //
    function Run() {

        global $XCOW_B;

        $status = NULL;

	$this->theFile = '';

        //
        // if the flag is set, go for it
        // otherwise proceed to the view and show a form for a new upload
        //
        if (isset ($this->ses['request']['param']['flag'])) {

		// image init
		$this->file_tmp = $this->ses['request']['file_info']['file']['tmp_name'];
		$this->file_name = basename($this->ses['request']['file_info']['file']['name']);
		$this->file_size = $this->ses['request']['file_info']['file']['size'];

		$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
		$this->file_id = md5($this->file_size.microtime().date("r").mt_rand(11111, 99999));
		$this->file_upload_name = $this->file_id.".".$this->file_ext;
		$this->file_upload_location = $XCOW_B['upload_base']."/".$this->file_upload_name;

		// get status
		$uploadStatus = getUploadStatus($this->file_tmp, $this->file_name, $this->file_ext, $this->file_size, $this->file_upload_location);	

		// if upload OK 
		// => move file to 'destination'
		if ($uploadStatus['status'] == 1) {
			moveUploadFile ($this->file_upload_location, $XCOW_B['upload_destination_dir'], $this->file_upload_name);

			$this->theFile = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;

			// now put $this->theFile in the database for future reference!
		}

		$status = $uploadStatus['message'];
	}

       	$this->ses['response']['param']['max_upload'] = $XCOW_B['max_upload'];
      	$this->ses['response']['param']['status'] = $status;
      	$this->ses['response']['param']['theFile'] = $this->theFile;

     }

}

?>
