<?

#########
# ERRORS
#########

# echo three types of errors in the logfile
# - program errors
# - program exceptions
# - mysql errors

# Turn error & exception handling on
set_error_handler('catchError');
set_exception_handler('catchException');

# catch errors
function catchError($errno, $errstr, $errfile, $errline) {

    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    /*
    switch ($errno) {
	    case E_USER_ERROR:
		# TODO: make it work! log2file & redirect to status page!
		echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
		echo "  Fatal error on line $errline in file $errfile";
		echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
		echo "Aborting...<br />\n";
		exit(1);
		break;

	    case E_USER_WARNING:
		echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
		break;

	    case E_USER_NOTICE:
		echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
		break;

	    default:
		echo "<b>My UNKNOWN</b> Unknown error type: [$errno] $errstr<br />\n";
		break;
    }
    */

    echoProgramError($errfile, $errline, $errno, $errstr);

    /* Don't execute PHP internal error handler */
    return true;
}

# TODO: what about exceptions?
function catchException($exception) {
	echoProgramException($exception->getFile(), $exception->getLine(), $exception->getCode(), $exception->getMessage());
}

# my mysql error catching
function catchMysqlError($function, $mysql_link) {
	echoMysqlError($function, mysql_errno($mysql_link), mysql_error($mysql_link));
}

#########
# LOGGING
#########

function openLogFile() {
    global $XCOW_B;

    $XCOW_B['log_handler'] = fopen($XCOW_B['log_file'], 'a');
}

function closeLogFile() {
    global $XCOW_B;

    fclose($XCOW_B['log_handler']);
}

function log2file($message) {
    global $XCOW_B;

    $out = strftime("%A %d-%b-%y %T %Z", time()).": ".$message."\n";
    fwrite($XCOW_B['log_handler'], $out, strlen($out));

}

# error logging
function echoProgramError($file, $line, $errorNr, $errorTxt) {
	log2file( "[".$file.":".$line."] Program Error: ".$errorNr.", ".$errorTxt."\n" );
}

function echoProgramException($file, $line, $errorNr, $errorTxt) {
	log2file( "[".$file.":".$line."] Program Exception: ".$errorNr.", ".$errorTxt."\n" );
}

# mysql logging
function echoMysqlError($function, $errorNr, $errorTxt) {
	log2file( "[".$function."] Mysql Error: ".$errorNr.", ".$errorTxt."\n" );
}

##################
# Header functions
##################

#
# Status
#
function getStatus($status) {
	if (isset ($status)) {
		return ($status);
	}
	else {
		return "200 OK";
	}
}

#
# Microtime
#
function getMicrotime() {
	return array_sum(explode(' ',microtime()));
}

#
# Request
#
function getRequest($ses) {
	$requestString = "";
	if ($ses['request']['REST']['object']) {
		$requestString .= "/".$ses['request']['REST']['object'];
	}
	if ($ses['request']['REST']['param']) {
		$requestString .= "/".$ses['request']['REST']['param'];
	}
	if ($ses['request']['REST']['function']) {
		$requestString .= "/".$ses['request']['REST']['function'];
	}

	return $requestString;
}


##############
# Divers
##############

function get_id_from_multi_array($array, $attribute, $value) {
	$id = 0;
	if (is_array($array)) {
		foreach ($array as $key => $sub_array) {
			if ($sub_array[$attribute] == $value) {
				$id = $key;
				break;
			}
		}
	}
	return $id;
}

function get_list_from_multi_array($array, $attribute, $value) {
        $list = array();
        if (is_array($array)) {
                foreach ($array as $key => $sub_array) {
                        if ($sub_array[$attribute] == $value) {
                                $list[] = $key;
                        }
                }
        }
        return $list;
}

function unique_multi_array($array, $sub_key) {
    $target = array();
    $existing_sub_key_values = array();
    foreach ($array as $key=>$sub_array) {
        if (!in_array($sub_array[$sub_key], $existing_sub_key_values)) {
            $existing_sub_key_values[] = $sub_array[$sub_key];
            $target[$key] = $sub_array;
        }
    }
    return $target;
}

function unique_multi_array_all_fields($array) {
    $target = array();
    $existing_sub_array = array();
    foreach ($array as $key=>$sub_array) {
	$match = "";
	foreach ($sub_array as $sk => $sv) {
		$match .= $sk.$sv;
	}
        if (!in_array($match, $existing_sub_array)) {
            $existing_sub_array[] = $match;
            $target[$key] = $sub_array;
        }
    }
    return $target;
}

function exploreDir($dir) {

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

            // recurse directories
            //if (is_dir($dir."/".$file)) {
            //    $files = array_merge($files,exploreDir($dir."/".$file));
            //}

            // add file
            if (is_file($dir."/".$file)) {
                $files[]=$dir."/".$file;
            }

        }

        closedir($openDir);

    }

    return $files;

}

function timeDiff($timestamp) {
	$currentTime = time();

	$displayTime = $currentTime - $timestamp;
	if ($displayTime == 1) {$period = language("base_word_second");} else {$period = language("base_word_seconds");}

	if ( floor(($currentTime - $timestamp) / 60) >= 1) { $displayTime = floor(($currentTime - $timestamp) / 60); if ($displayTime == 1) {$period = language("base_word_minute");} else {$period = language("base_word_minutes");} }
	if ( floor(($currentTime - $timestamp) / 3600) >= 1) { $displayTime = floor(($currentTime - $timestamp) / 3600); if ($displayTime == 1) {$period = language("base_word_hour");} else {$period = language("base_word_hours");}}
	if ( floor(($currentTime - $timestamp) / 86400) >= 1) { $displayTime = floor(($currentTime - $timestamp) / 86400); if ($displayTime == 1) {$period = language("base_word_day");} else {$period = language("base_word_days");} }

	return ($displayTime." ".$period);
}

function timeDisplay($timestamp) {

        $timeArray = getdate($timestamp);
        $monthArray = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');

        return $timeArray[mday]." ".$monthArray[$timeArray[mon]-1]." ".$timeArray[year];

}

function timeDiff2($timestamp) {
	$currentTime = time();

	$displayTime = $currentTime - $timestamp;
	$period = language("base_word_second_short");

	if ( floor(($currentTime - $timestamp) / 60) >= 1) { $displayTime = floor(($currentTime - $timestamp) / 60); $period = language("base_word_minute_short"); }
	if ( floor(($currentTime - $timestamp) / 3600) >= 1) { $displayTime = floor(($currentTime - $timestamp) / 3600); $period = language("base_word_hour_short"); } 
	
	if ( floor(($currentTime - $timestamp) / 86400) >= 1) {
		return timeDisplay2($timestamp);
	} 
	else {
		return ($displayTime.$period);
	}
}

function timeDisplay2($timestamp) {

	$timeArray = getdate($timestamp);
	$languageTemplate = array();
	$languageTemplate['day'] = $timeArray[mday];
	$timeString = language_template('base_word_day_month_'.$timeArray[mon], $languageTemplate);

	$nowArray = getdate();
	if ($nowArray[year] == $timeArray[year]) {
		return $timeString;
	}
	else {
		return $timeString.", ".$timeArray[year];
	}

}

function isValid ($string) {

	$valid_chars = 'a-zA-Z0-9';
	return preg_match("/^[$valid_chars]*$/", "$string");
}

function isValidUser ($string) {

	if (strlen($string) < 2) {
		return 0;
	}

	$valid_chars = 'a-zA-Z0-9_.+-@';
	return preg_match("/^[$valid_chars]*$/", "$string");
}

function isValidGroup ($string) {

	if (strlen($string) < 2) {
		return 0;
	}

	$valid_chars = 'a-zA-Z0-9_,.\s+-';
	return preg_match("/^[$valid_chars]*$/", "$string");
}

function isValidPass ($string) {

	if (strlen($string) < 2) {
		return 0;
	}

	return 1;
}

function generatePass() {
    $length = 8;
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $pass = "";    

    for ($i = 0; $i < $length; $i++) {
        $pass .= $characters[mt_rand(0, strlen($characters)-1)];
    }

    return $pass;
}

function generatePin($length = 5, $characters = "0123456789") {
    $pin = "";    

    for ($i = 0; $i < $length; $i++) {
        $pin .= $characters[mt_rand(0, strlen($characters)-1)];
    }

    return $pin;
}

?>
