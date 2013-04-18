<?php
// Hugh Bragg
function getMimeType($filename)
{
	$mimetype = false;
	if(function_exists('finfo_open')) {
		// open with FileInfo
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimetype = finfo_file($finfo, $filename);
		finfo_close($finfo);
	} elseif(function_exists('exif_imagetype')) {
		// open with EXIF
		$extifType = exif_imagetype($filename);
		$mimetype =  image_type_to_mime_type($extifType);
	} elseif(function_exists('getimagesize')) {
		// open with GD
		$info = getimagesize($filename);
		$mimetype = $info['mime'];
	} elseif(function_exists('mime_content_type')) {
		// PHP < 5.3
		$mimetype = mime_content_type($filename);
	}
	return $mimetype;
}