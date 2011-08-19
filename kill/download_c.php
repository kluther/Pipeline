<?php
require_once('./../../global.php');

$fileID = Filter::numeric($_GET['f']);
$file = Upload::load($fileID);

if($file != null) {
	// script borrowed from http://elouai.com/force-download.php

	// required for IE, otherwise Content-disposition is ignored
	if(ini_get('zlib.output_compression'))
	  ini_set('zlib.output_compression', 'Off');

	// set headers
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header("Content-Type: ".$file->getMime());
	// change, added quotes to allow spaces in filenames, by Rajkumar Singh
	header("Content-Disposition: attachment; filename=\"".$file->getOriginalName()."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$file->getSize());
	
	// read file
	readfile(Url::upload().'/'.$file->getStoredName());
	exit();
}