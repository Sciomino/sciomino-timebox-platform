<?

class importPhoto extends control {

    function Run() {

        global $XCOW_B;

		$this->dir = $this->ses['request']['param']['dir'];
		# mode
		# - all: do import all
		# - onlyDisplay: to show data in browser, don't store anything
		$this->mode = $this->ses['request']['param']['mode'];
		if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		$this->offset = $this->ses['request']['param']['offset'];
		if (! isset($this->offset)) {$this->offset = 0;}
		$this->limit = $this->ses['request']['param']['limit'];
		if (! isset($this->limit)) {$this->limit = 0;}

		# import
		if (! $XCOW_B['sciomino']['import-done']) {

			# ok, first read files
			$dir = "";
			if (isset($this->dir)) {
				$dir = $XCOW_B['sciomino']['import-update-directory']."/".$this->dir;
			}
			else {
				# default dirname (could also read dirname form config...)
				$dir = $XCOW_B['sciomino']['import-update-directory']."/pasfotos";
			}
			$files = exploreDir($dir);

			# upload in parts
			if ($this->limit != 0) {
				$files = array_slice($files, $this->offset, $this->limit);
			}
	 
			if (count($files) > 0) {
				$count = 0; 
				foreach ($files as $file) {
					# get id
					# - only works with .jpg file
					$fileId = basename($file, ".jpg");

					# skip empty file
					if (filesize($file) == 0) {
						echo "> skip empty file: ".$fileId."<br/>\n";
						continue;
					}

					# user constructs
					$this->userId = 0;
					$this->userPhoto = "";
					$this->user = array();
					$this->annotation = array();

					# userName is fileId!
					$userName = $fileId;
					$userRef = getUserIdFromUserName($userName);
					# skip if user does not exist
					if (empty($userRef)) {
						echo "> skip unknown user: ".$fileId."<br/>\n";
						continue;
					}

					# start
					echo "> processing: ".$fileId."...";

					# file basics
					$this->file_name = basename($file);
					$this->file_ext = strtolower(substr(strrchr($this->file_name, "."), 1));
					$this->file_id = md5(microtime().date("r").mt_rand(11111, 99999));
					$this->file_upload_name = $this->file_id.".".$this->file_ext;

					$this->annotation['photo'] = $XCOW_B['upload_destination_url']."/".$this->file_upload_name;

					# about the user
					$this->user = UserApiListUserByReference($userRef);
					$this->user = current($this->user);
					$this->userId = $this->user['Id'];
					$this->userPhoto = $this->user['photo'];
					
					# skip if photo exists
					if ($this->userPhoto != "") {
						echo " *** photo exists (".$this->userPhoto.") *** <br/>\n";
						continue;
					}
					
					if ($this->userId != 0) {
						if ($this->mode == "all") {
							$this->annotationId = UserApiUpdateUserAnnotationListByUser($this->annotation, $this->userId);

							# foto
							copy ($file, $XCOW_B['upload_destination_dir']."/".$this->file_upload_name);
							createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 96, 96);
							createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 48, 48);
							createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, 32, 32);
				
							// reset original photo size
							if ($XCOW_B['sciomino']['original-photo-size'] != 0) {
								createThumbnail($XCOW_B['upload_destination_dir'], $this->file_upload_name, $XCOW_B['sciomino']['original-photo-size'], $XCOW_B['sciomino']['original-photo-size']);
								moveUploadFile ($XCOW_B['upload_destination_dir']."/".$XCOW_B['sciomino']['original-photo-size']."x".$XCOW_B['sciomino']['original-photo-size']."_".$this->file_upload_name, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
							}

						}
					}
					
					# debug
					if ($this->mode == "onlyDisplay") {
						echo "<br/>\nfile: ".$file."<br/>";
						echo "\nUserName: ".$userName."<br/>";
						echo "\nUserRef: ".$userRef."<br/>";
						echo "\nUserId: ".$this->userId."<br/>";
						echo "\nNewPhoto: ".$this->file_name."<br/>";
						echo "\nNewPhotoName: ".$this->file_upload_name."<br/>";
					}

					#end & settle down
					echo " done<br/>\n";
					$count++;
					usleep(100000);
					if ($this->mode == "onlyDisplay") {
						if ($count > 9) {break;}
					}
				}
				$status = "Import done. ".$count." photos";
			}
			else {
				$status = "No files to import";
			}
		}
		else {
			$status = "Import not allowed";
		}

		# output
		$this->ses['response']['param']['status'] = $status;

    }

}

?>
