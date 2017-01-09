<?php

#
# Functions to scale graphics with GD library
#
# EXAMPLE: schaal image binnen 100 x 100
# $wantedWidth = 100;
# $wantedHeight = 100;
# $wantedPrefix = "100x100_";
# $srcInfo = getGfxInfo($XCOW_B['upload_destination_dir']."/".$this->file_upload_name);
# $srcImage = getGfxSource($srcInfo['type'], $XCOW_B['upload_destination_dir']."/".$this->file_upload_name);
# # src => des
# $desInfo = GfxScaleInBorder($srcInfo['width'], $srcInfo['height'], $wantedWidth, $wantedHeight);
# $desImage = getGfxDestination($srcInfo['type'], $desInfo['width'], $desInfo['height']);
# GfxDestinationScale($desImage, $desInfo['width'], $desInfo['height'], $srcImage, $srcInfo['width'], $srcInfo['height']);
# setGfxDestination($desImage, $srcInfo['type'], $wantedPrefix, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
#
#
# EXAMPLE: crop & schaal image binnen 100 x 100
# $wantedWidth = 100;
# $wantedHeight = 100;
# $wantedPrefix = "100x100_";
# $srcInfo = getGfxInfo($XCOW_B['upload_destination_dir']."/".$this->file_upload_name);
# $srcImage = getGfxSource($srcInfo['type'], $XCOW_B['upload_destination_dir']."/".$this->file_upload_name);
# # src => crop
# $cropInfo = GfxCropToRatio($srcInfo['width'], $srcInfo['height'], $wantedWidth, $wantedHeight);
# $cropImage = getGfxDestination($srcInfo['type'], $cropInfo['width'], $cropInfo['height']);
# GfxDestinationCrop($cropImage, $srcImage, $cropInfo['x'], $cropInfo['y'], $cropInfo['width'], $cropInfo['height']);
# # crop => des
# $desInfo = GfxScaleInBorder($cropInfo['width'], $cropInfo['height'], $wantedWidth, $wantedHeight);
# $desImage = getGfxDestination($srcInfo['type'], $desInfo['width'], $desInfo['height']);
# GfxDestinationScale($desImage, $desInfo['width'], $desInfo['height'], $cropImage, $cropInfo['width'], $cropInfo['height']);
# setGfxDestination($desImage, $srcInfo['type'], $wantedPrefix, $XCOW_B['upload_destination_dir'], $this->file_upload_name);
#

function createThumbnail($dir, $file, $width, $height) {
	$wantedWidth = $width;
	$wantedHeight = $height;
	$wantedPrefix = $width."x".$height."_";

	$srcInfo = getGfxInfo($dir."/".$file);
	$srcImage = getGfxSource($srcInfo['type'], $dir."/".$file);

	# src => crop
	$cropInfo = GfxCropToRatio($srcInfo['width'], $srcInfo['height'], $wantedWidth, $wantedHeight);
	$cropImage = getGfxDestination($srcInfo['type'], $cropInfo['width'], $cropInfo['height']);
	GfxDestinationCrop($cropImage, $srcImage, $cropInfo['x'], $cropInfo['y'], $cropInfo['width'], $cropInfo['height']);

	# crop => des
	$desInfo = GfxScaleInBorder($cropInfo['width'], $cropInfo['height'], $wantedWidth, $wantedHeight);
	$desImage = getGfxDestination($srcInfo['type'], $desInfo['width'], $desInfo['height']);
	GfxDestinationScale($desImage, $desInfo['width'], $desInfo['height'], $cropImage, $cropInfo['width'], $cropInfo['height']);
	setGfxDestination($desImage, $srcInfo['type'], $wantedPrefix, $dir, $file);
}


function getGfxInfo ($file_name) {

	$types = array (
		IMAGETYPE_PNG => "PNG",
		IMAGETYPE_JPEG => "JPG",
		IMAGETYPE_GIF => "GIF"
	);

	list($width, $height, $type) = getimagesize($file_name);

	$gfx = array();
	$gfx['width'] = $width;
	$gfx['height'] = $height;
	
	$gfx['type'] = $types[$type];

	return $gfx;
}

function getGfxSource($type, $file_name) {
	$image = "";

	switch ($type) {
		case "PNG":
			$image = imagecreatefrompng($file_name);
			break;
		case "JPG":
			$image = imagecreatefromjpeg($file_name);
			break;
		case "GIF":
			$image = imagecreatefromgif($file_name);
			break;

	}

	return $image;
}

function getGfxDestination($type, $width, $height) {

	$image = imagecreatetruecolor($width, $height);

	if ($type == "PNG") {
		imagealphablending($image, false);
		imagesavealpha($image, true);
	}
	
	return $image;
}

function setGfxDestination($image, $type, $prefix, $dir_name, $file_name) {
	switch ($type) {
		case "PNG":
			imagepng($image, $dir_name."/".$prefix.$file_name, 1);
			break;
		case "JPG":
			imagejpeg($image, $dir_name."/".$prefix.$file_name, 90);
			break;
		case "GIF":
			imagegif($image, $dir_name."/".$prefix.$file_name);
			break;

	}

	return $image;
}

function GfxScaleInBorder($width, $height, $borderx, $bordery) {

	$xscale=$width/$borderx;
	$yscale=$height/$bordery;

	if ($yscale>$xscale){
		$new_width = round($width * (1/$yscale));
		$new_height = round($height * (1/$yscale));
	}
	else {
		$new_width = round($width * (1/$xscale));
		$new_height = round($height * (1/$xscale));
	}

	$gfx = array();
	$gfx['width'] = $new_width;
	$gfx['height'] = $new_height;

	return $gfx;
}

function GfxCropToRatio($width, $height, $borderx, $bordery) {

	$scale = min(($width/$borderx),($height/$bordery));

	$cropX = round($width-($scale*$borderx));
	$cropY = round($height-($scale*$bordery));

	$gfx = array();
	$gfx['x'] = round($cropX/2);
	$gfx['y'] = round($cropY/2);
	$gfx['width'] = round($width - $cropX);
	$gfx['height'] = round($height - $cropY);

	return $gfx;
}


function GfxDestinationScale($des, $desW, $desH, $src, $srcW, $srcH) {

	imagecopyresampled($des, $src, 0, 0, 0, 0, $desW, $desH, $srcW, $srcH);

}

function GfxDestinationCrop($des, $src, $srcX, $srcY, $srcW, $srcH) {

	imagecopy($des, $src, 0, 0, $srcX, $srcY, $srcW, $srcH);

}

?>
