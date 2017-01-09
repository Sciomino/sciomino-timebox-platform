<?php
    $file_name = $session['response']['param']['file'];
    if ($file_name) {
    	readfile($file_name);
    }
    else {
    	echo language($session['response']['param']['status']);
    }
?>
