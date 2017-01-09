<?

class fileDownload extends control {

    function Run() {

        global $XCOW_B;

	// init
	$file = $this->ses['request']['param']['file'];

	// about the file
	//
	// $file_parts = pathinfo($file);
	// $file_ext = $file_parts['extension'];

	$file_name = basename($file);
	$file_location = $XCOW_B['upload_destination_dir']."/".$file_name;

    	$mimetype = "";
    	$gmtime = gmdate("D, d M Y H:i:s")." GMT";

	#
	# we do not allow ".." in the filename, this is a possible security risk
	#
	#if ( (file_exists($file_name)) && (!is_integer(strpos ($file_name, "..")) ) ) {
	if ( file_exists($file_location) ) {

		$file_size = filesize($file_location);

		#
		# should return a list of headers to keep this xcow compatible;
		#
		#$this->ses['response']['header'] = "Content-Type: $mimetype";

		#
		# instead we ouput the file directly to the client browser
		#
		header("Content-Type: $mimetype");
		header("Content-Length: $file_size");
		header("Content-Disposition: attachment; filename=$file_name");

		# no-cache, please... but two options do not seem to work...
		header("Expires: Sun, 4 Feb 2001 19:58:00 GMT");
		header("Last-Modified: $gmtime");
		#header("Cache-Control: no-store, no-cache, must-revalidate");
		#header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		# here i come
		$this->ses['response']['param']['file'] = $file_location;

	}
        else {
                $this->ses['response']['param']['status'] = "base_status_file_download_notfound";
        }

    }

}

?>
