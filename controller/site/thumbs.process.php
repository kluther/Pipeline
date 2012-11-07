<?php

// temp variable for uploaded file (and path)
$uploadedFile = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// get MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $uploadedFile);
finfo_close($finfo);

// get extension
$ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);

// validate MIME type and extension
if( !Upload::isAllowedExtension($ext) ) {
	// delete the file we just uploaded and send us back
	chown($uploadedFile, 666);
	unlink($uploadedFile);
	die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "That file type is not allowed."}, "id" : "'.$fileName.'"}');
}

// is this server running Windows or *nix?
$isWindows = in_array(PHP_OS, array('WIN32','WINNT','Windows'));

// thumbnail for Flash/video uploads
$videoThumbFile = THUMB_PATH.'/'.pathinfo($fileName,PATHINFO_FILENAME).'.jpg';

// generate preview, if possible
$previewFile = PREVIEW_PATH.'/'.pathinfo($fileName,PATHINFO_FILENAME).'.flv';

switch($mime) {
	// it's an image
	case 'image/jpeg':
	case 'image/pjpeg':
	case 'image/gif':
	case 'image/png':
		// calculate resized width and height
		list($orig_width, $orig_height) = @getimagesize($uploadedFile);
                $scale = min(
                Upload::THUMB_MAX_WIDTH / $orig_width,
                Upload::THUMB_MAX_HEIGHT / $orig_height
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
                        ) && $write_image($image_p, THUMB_PATH.'/'.$fileName);
                        // clean up
                @imagedestroy($image);
                @imagedestroy($image_p);
                        if(!$success) {
                                die('{"jsonrpc" : "2.0", "error" : {"code": 105, "message": "Unable to create image thumbnail."}, "id" : "'.$fileName.'"}');
                        }
                        break;

	// it's flash
	case 'application/x-shockwave-flash':
	case 'application/octet-stream':
		// only generate thumbs for .swf (.fla also uses these mime types)
		if($ext == 'swf') {
			list($orig_width, $orig_height) = @getimagesize($uploadedFile);
			$scale = min(
				Upload::THUMB_MAX_WIDTH / $orig_width,
				Upload::THUMB_MAX_HEIGHT / $orig_height
			);
			if ($scale > 1) {
				$scale = 1;
			}
			$new_width = $orig_width * $scale;
			$new_height = $orig_height * $scale;
			if($isWindows) {
				echo exec(SYSTEM_PATH.'/lib/swfrender '.$uploadedFile.' -X '.$new_width.' -Y '.$new_height.' -o '.$videoThumbFile); // generate thumb
			} else {
				echo exec(SYSTEM_PATH.'/lib/swftools/swfrender '.$uploadedFile.' -X '.$new_width.' -Y '.$new_height.' -o '.$videoThumbFile); // generate thumb
			}
		}
		break;
		
	// it's video
	case 'video/mpeg': // .mpg
	case 'video/mp4':  // .mp4
        case 'video/3gpp':  // .3gp
        case 'video/quicktime': // .mov
	case 'video/x-msvideo': // .avi
	case 'video/x-flv':
		if($isWindows) {
			echo exec(SYSTEM_PATH.'/lib/ffmpeg -ss 00:00:02 -i '.$uploadedFile.' -y -f image2 -sameq -s '.Upload::THUMB_MAX_WIDTH.'x'.Upload::THUMB_MAX_HEIGHT.' '.$videoThumbFile); // generate thumb
		} else {
			echo exec('ffmpeg -ss 00:00:02 -i '.$uploadedFile.' -y -f image2 -sameq -s '.Upload::THUMB_MAX_WIDTH.'x'.Upload::THUMB_MAX_HEIGHT.' '.$videoThumbFile); // generate thumb
		}
		// NO break
	// non-FLV videos also need to generate a preview
	case 'video/mpeg': // .mpg
	case 'video/mp4':  // .mp4
        case 'video/3gpp':  // .3gp
        case 'video/quicktime': // .mov
	case 'video/x-msvideo': // .avi
		if($isWindows) {
                        echo exec(SYSTEM_PATH.'/lib/ffmpeg -i '.$uploadedFile.' -ar 22050 -vcodec flv '.$previewFile); // generate preview
		} else {
			echo exec('ffmpeg -i '.$uploadedFile.' -ar 22050 -vcodec flv '.$previewFile); // generate preview
		}
		break;
}
