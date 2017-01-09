<?php

function language($key) {

	global $XCOW_B;

	$value = $key;

	if (array_key_exists($key, $XCOW_B['language'])) {
		$value = $XCOW_B['language'][$key];
	}

	return $value;
}

function language_template($key, $filler) {

	global $XCOW_B;

	$template = language($key);

	foreach ($filler as $fillKey => $fillValue) {
		$template = str_replace("\$".$fillKey."\$", $fillValue, $template);
	}
	
	return $template;
}

function mail_template($file, $filler, $language) {

	global $XCOW_B;

	// get template from file
	$mail_body = $XCOW_B['base'] ."/data/etc/mail/".$language."/".$file;
	$template = file_get_contents($mail_body);

	foreach ($filler as $fillKey => $fillValue) {
		$template = str_replace("\$".$fillKey."\$", $fillValue, $template);
	}

	// wrap body template in a skin
	$mail_skin = $XCOW_B['base'] ."/data/etc/mail/skin/".$XCOW_B['sciomino']['skin']."/mail_template_".$language;
	$skin = file_get_contents($mail_skin);
	$skin = str_replace("\$body\$", $template, $skin);
	
	return $skin;
}

?>
