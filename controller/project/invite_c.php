<?php
require_once("../../global.php");

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

// kick us out if slug invalid
if($project == null) {
	header('Location: '.Url::error());
	exit();
}

// make sure we have permission
if ( !Session::isAdmin() &&
		!$project->isMember(Session::getUserID()) &&
		!$project->isTrusted(Session::getUserID()) && 
		!$project->isCreator(Session::getUserID()) ) {
	header('Location: '.Url::error());
	exit();	
}

$soup = new Soup();
$soup->set('project', $project);
$soup->render('project/page/invite');