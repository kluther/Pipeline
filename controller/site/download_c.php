<?php
require_once("../../global.php");

$fileID = Filter::numeric($_GET['fi']);
$fileName = Filter::text($_GET['fn']);

$upload = Upload::load($fileID);
if( ($upload == null) ||
		($fileName != $upload->getOriginalName()) ){
	header('Location: '.Url::error());
	exit();
}

$fileURL = Url::uploads().'/'.$upload->getStoredName();

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-Type: '.$upload->getMime().'"');
header('Content-Disposition: attachment; filename="'.$upload->getOriginalName().'"');
header("Content-Transfer-Encoding: binary");
header('Content-Length: '.$upload->getSize());
readfile($fileURL);