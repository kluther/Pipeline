<?php
require_once("../../global.php");

if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();
}

$user = Session::getUser();

if(isset($_POST['action'])) {

	$action = Filter::text($_POST['action']);
	if($action == 'remove-picture') {
		// remove the pictures
		// - main
		$mainPictureURL = USER_PICTURE_PATH.'/'.$user->getPicture();
		chown($mainPictureURL, 666);
		unlink($mainPictureURL);
		
		// - large
		$largePictureURL = USER_PICTURE_LARGE_PATH.'/'.$user->getPicture();
		chown($largePictureURL, 666);
		unlink($largePictureURL);		
		
		// - small
		$smallPictureURL = USER_PICTURE_SMALL_PATH.'/'.$user->getPicture();
		chown($smallPictureURL, 666);
		unlink($smallPictureURL);		
		
		// remove DB record
		$user->setPicture(null);
		$user->save();
		
		// send us back
		Session::setMessage("Your picture has been removed.");
		$json = array( 'success' => '1' );
		echo json_encode($json);		
	} else {
		$json = array('error' => 'Unrecognized action.');
		exit(json_encode($json));
	}
} else {

/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Settings
//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$targetDir = USER_PICTURE_PATH;

//$cleanupTargetDir = false; // Remove old files
//$maxFileAge = 60 * 60; // Temp file age in seconds

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files
/* this doesn't really work by now
	
if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
	while (($file = readdir($dir)) !== false) {
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// Remove temp files if they are older than the max age
		if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
			@unlink($filePath);
	}

	closedir($dir);
} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
*/

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
} else {
	// Open temp file
	$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

// only generate thumbs/previews if this is the last chunk
if($chunk == ($chunks-1)) {

	// temp variable for uploaded file (and path)
	$uploadedFile = $targetDir . DIRECTORY_SEPARATOR . $fileName;

	// get MIME type
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($finfo, $uploadedFile);
	finfo_close($finfo);

	// get extension
	$ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);

	// validate MIME type and extension
	if( !Upload::isAllowedMime($mime) || !Upload::isAllowedExtension($ext) ) {
		// delete the file we just uploaded and send us back
		chown($uploadedFile, 666);
		unlink($uploadedFile);
		die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "That file type is not allowed."}, "id" : "'.$fileName.'"}');
	}

	// make large thumb

	// calculate resized width and height
	list($orig_width, $orig_height) = @getimagesize($uploadedFile);
	$scale = min(
		User::PICTURE_LARGE_MAX_WIDTH / $orig_width,
		User::PICTURE_LARGE_MAX_HEIGHT / $orig_height
	);
	if ($scale > 1) {
		$scale = 1;
	}
	$new_width = $orig_width * $scale;
	$new_height = $orig_height * $scale;
	// create resized image
	$image_p = @imagecreatetruecolor($new_width, $new_height);
	if( ($mime == 'image/jpeg') || ($mime == 'image/jpg') ) {
		$image = @imagecreatefromjpeg($uploadedFile);
		$write_image = 'imagejpeg';
	} elseif($mime == 'image/gif') {
		$image = @imagecreatefromgif($uploadedFile);
		$write_image = 'imagegif';
	} elseif($mime == 'image/png') {
		$image = @imagecreatefrompng($uploadedFile);
		$write_image = 'imagepng';
	} else {
		$image = null;
	}
	$success = $image && @imagecopyresampled(
		$image_p,
		$image,
		0, 0, 0, 0,
		$new_width,
		$new_height,
		$orig_width,
		$orig_height
	) && $write_image($image_p, USER_PICTURE_LARGE_PATH.'/'.$fileName);
	// clean up
	@imagedestroy($image);
	@imagedestroy($image_p);
	if(!$success) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 105, "message": "Unable to create large image thumbnail."}, "id" : "'.$fileName.'"}');
	}

	// make small thumb
	// calculate resized width and height
	list($orig_width, $orig_height) = @getimagesize($uploadedFile);
	$scale = min(
		User::PICTURE_SMALL_MAX_WIDTH / $orig_width,
		User::PICTURE_SMALL_MAX_HEIGHT / $orig_height
	);
	if ($scale > 1) {
		$scale = 1;
	}
	$new_width = $orig_width * $scale;
	$new_height = $orig_height * $scale;
	// create resized image
	$image_p = @imagecreatetruecolor($new_width, $new_height);
	if( ($mime == 'image/jpeg') || ($mime == 'image/jpg') ) {
		$image = @imagecreatefromjpeg($uploadedFile);
		$write_image = 'imagejpeg';
	} elseif($mime == 'image/gif') {
		$image = @imagecreatefromgif($uploadedFile);
		$write_image = 'imagegif';
	} elseif($mime == 'image/png') {
		$image = @imagecreatefrompng($uploadedFile);
		$write_image = 'imagepng';
	} else {
		$image = null;
	}
	$success = $image && @imagecopyresampled(
		$image_p,
		$image,
		0, 0, 0, 0,
		$new_width,
		$new_height,
		$orig_width,
		$orig_height
	) && $write_image($image_p, USER_PICTURE_SMALL_PATH.'/'.$fileName);
	// clean up
	@imagedestroy($image);
	@imagedestroy($image_p);
	if(!$success) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 105, "message": "Unable to create small image thumbnail."}, "id" : "'.$fileName.'"}');
	}	
	
	// remove old pictures, if they exist
	if($user->getPicture() != null) {
		// - main
		$mainPictureURL = USER_PICTURE_PATH.'/'.$user->getPicture();
		chown($mainPictureURL, 666);
		unlink($mainPictureURL);
		
		// - large
		$largePictureURL = USER_PICTURE_LARGE_PATH.'/'.$user->getPicture();
		chown($largePictureURL, 666);
		unlink($largePictureURL);		
		
		// - small
		$smallPictureURL = USER_PICTURE_SMALL_PATH.'/'.$user->getPicture();
		chown($smallPictureURL, 666);
		unlink($smallPictureURL);		
		
		// remove DB record
		$user->setPicture(null);
		$user->save();	
	}
	
	// update database
	$user->setPicture($fileName);
	$user->save();
}

// Return JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "'.$fileName.'"}');

}