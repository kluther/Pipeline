<?php
require_once("../../global.php");

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);


// kick us out if slug invalid
if($project == null) {
	header('Location: '.Url::error());
	exit();
}

// validate task
$taskID = Filter::numeric($_GET['t']);
$task = Task::load($taskID);
if($task == null) {
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

// // validate username
// $username = Filter::text($_GET['u']);
// $user = User::loadByUsername($username);

// // check if user has accepted task
//$accepted = Accepted::getByUserID(Session::getUserID(), $taskID);
// if($accepted == null) {
	// header('Location: '.Url::error());
	// exit();
// }

//$updates = Update::getByAcceptedID($accepted->getID());

// get existing updates
$accepted = Accepted::getByUserID(Session::getUserID(), $taskID);
if($accepted == null) {
	header('Location: '.Url::error());
	exit();
}
$updates = Update::getByAcceptedID($accepted->getID());

$soup = new Soup();
$soup->set('project', $project);
$soup->set('task', $task);
$soup->set('updates', $updates);
//$soup->set('user', $user);
//$soup->set('accepted', $accepted);
$soup->render('project/page/updateNew');