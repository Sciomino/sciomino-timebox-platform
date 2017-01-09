<?php

#
# set queue entry
#
function setQueueEntry ($file_id, $buffer) {

        global $XCOW_B;

        $file = $XCOW_B['queue']."/".$file_id;

	# use 'x' to not overwrite an existing file
        $fp = @fopen($file, "x");

        if ($fp) {
                fwrite($fp, serialize($buffer));
                fclose($fp);
                return 1;
        }
        else {
                return 0;
        }

}

#
# get queue entry
#
function getQueueEntry () {

        global $XCOW_B;
	$queue = array();
	$entry = array();
	$buffer = "";

        $queue = readFilesFromDir($XCOW_B['queue'], 1);

        if (isset($queue) && count($queue) > 0) {

                $fp = @fopen($queue[0], "r");

                if ($fp) {
                        while (!feof($fp)) {
                                $buffer .= fgets($fp, 4096);
                        }

			$entry = unserialize($buffer);

                        fclose($fp);

                        unlink ($queue[0]);

                        return $entry;
                }
        }
        return 0;
}

#
# delete queue entry
#
function deleteQueueEntry ($file_id) {

        global $XCOW_B;

        $file = $XCOW_B['queue']."/".$file_id;

	if (is_file($file)) {
		unlink ($file);
	}
}

function readFilesFromDir($dir, $one) {

    $files = array();

    if ($openDir = opendir($dir)) {

        while (false !== ($file = readdir($openDir))) {

            // skip hidden files and . and .. directories
            if ($file[0]==".") {
                continue;
            }

            // do not follow links
            if (is_link($dir."/".$file)) {
                continue;
            }

            // add file
            if (is_file($dir."/".$file)) {
                $files[]=$dir."/".$file;
		if ($one) {
			break;
		}
            }

        }

        closedir($openDir);

    }

    return $files;

}

?>
