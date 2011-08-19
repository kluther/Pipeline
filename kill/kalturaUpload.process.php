<?php
require_once("../../global.php");

$kalturaID = Filter::text($_POST['kalturaID']);
$token = Filter::alphanum($_POST['token']);

if( ($kalturaID != null) && ($token != null) ) {
	$upload = new Upload(array(
		'kaltura_id' => $kalturaID,
		'creator_id' => Session::getUserID(),
		'token' => $token
	));
	$upload->save();
	$json = array( 'success' => '1' );
	echo json_encode($json);
} else {
	$json = array( 'error' => 'Upload failed.' );
	exit(json_encode($json));
}