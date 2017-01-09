<?php

#
# UPLOAD status
#
function getUploadStatus($file_tmp, $file_name, $file_ext, $file_size, $secure_file_name) {

        global $XCOW_B;

	$status = array();

        $statusBOOL = NULL;
        $statusMSG = "";

        log2file("UPLOAD: tmp=".$file_tmp.", name=".$file_name.", ext=".$file_ext.", size=".$file_size.", secure_name=".$secure_file_name);

        if ( (! isset($statusBOOL)) && ($file_name == "") ) {
		$statusBOOL = 0;
                $statusMSG = "base_status_file_upload_select";
        }
        if ( (! isset($statusBOOL)) && (! in_array($file_ext, $XCOW_B['valid_extensions'])) ) {
		$statusBOOL = 0;
                $statusMSG = "base_status_file_upload_extension";
        }
        if ( (! isset($statusBOOL)) && ($file_size > $XCOW_B['max_upload']) ) {
		$statusBOOL = 0;
                $statusMSG = "base_status_file_upload_big";
        }
        if ( (! isset($statusBOOL)) && (! move_uploaded_file($file_tmp, $secure_file_name)) ) {
		$statusBOOL = 0;
                $statusMSG = "base_status_file_upload_security";
        }
        if (! isset($statusBOOL)) {
		$statusBOOL = 1;
                $statusMSG = "base_status_file_upload_ok";
        }

	$status['status'] = $statusBOOL;
	$status['message'] = $statusMSG;

        return $status;
}



function moveUploadFile($secure_file_name, $destination_dir, $destination_file) {

        rename ($secure_file_name, $destination_dir."/".$destination_file);

}



?>
