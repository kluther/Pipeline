<?php
require_once("../../global.php");

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

// kick us out if slug invalid
if($project == null)
{
	header('Location: '.Url::error());
	exit();
}

//do not allow banned members to access project
$isBanned = ProjectUser::isBanned(Session::getUserID(),$project->getID());
if ($isBanned) {
        header('Location: '.Url::error());
	exit();	
}

// if private project, limit access to invited users, members, and admins
if($project->getPrivate()) {
	if(!Session::isAdmin() &&
		(!$project->isInvited(Session::getUserID())) &&
		(!$project->isMember(Session::getUserID())) &&
		(!$project->isTrusted(Session::getUserID())) &&
		(!$project->isCreator(Session::getUserID())) ) {
	header('Location: '.Url::error());
	exit();		
	}
}

$uploads = array();

$tasks = $project->getTasks();
if(!empty($tasks)) {	

	foreach($tasks as $t) {
		// get task uploads
		$taskUploads = Upload::getByTaskID($t->getID(), false);
		if(!empty($taskUploads)) {
			foreach($taskUploads as $tu) {
				$uploads[] = $tu;
			}
		}
		
		// get contrib uploads
		$updates = $t->getUpdates();
		if(!empty($updates)) {
			foreach($updates as $u) {
				$updateUploads = Upload::getByUpdateID($u->getID(), false);
				if(!empty($updateUploads)) {
					foreach($updateUploads as $uu) {
						$uploads[] = $uu;
					}
				}
			}
		}
	}
}

$totalNumUploads = count($uploads);

// page number, if any
if(empty($_GET['page']))
	$page = 1;
else
	$page = Filter::numeric($_GET['page']);

define('UPLOADS_PER_PAGE', 5);

$numPages = ceil($totalNumUploads/UPLOADS_PER_PAGE); // get # pages
if( ($numPages != 0) && ($page > $numPages) ) {
	// invalid page number
	header('Location: '.Url::error());
	exit();
}

// only show uploads for this page
$offset = ($page-1)*UPLOADS_PER_PAGE;
$uploads = array_slice($uploads, $offset, UPLOADS_PER_PAGE);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('uploads', $uploads);
$soup->set('page', $page);
$soup->set('numPages', $numPages);
$soup->set('totalNumUploads', $totalNumUploads);

$soup->render('project/page/files');