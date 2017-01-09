<?

class fileList extends control {

    function Run() {

        global $XCOW_B;

	$files = array();
	$urls = array();

	$files = exploreDir($XCOW_B['upload_destination_dir']);

	foreach ($files as $file) {
		$urls[] = $XCOW_B['upload_destination_url']."/".basename($file);
	}

	$this->ses['response']['param']['urls'] = $urls;

    }

}

?>
