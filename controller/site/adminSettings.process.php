<?php
require_once('./../../global.php');

// check project
$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

$action = Filter::text($_POST['action']);

if($action == "newType") {
    //code for handling new type
} elseif($action == "newDoc") {
    //code for handling new doc
} elseif($action == "changeCurrent"){
    //code for handling change of current doc
} else {
	$json = array( 'error' => 'Invalid action.' );
	exit(json_encode($json));
}