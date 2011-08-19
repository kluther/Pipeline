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
$updates = Update::getByAcceptedID($accepted->getID());

$soup = new Soup();
$soup->set('project', $project);
$soup->set('task', $task);
$soup->set('updates', $updates);
//$soup->set('user', $user);
//$soup->set('accepted', $accepted);
$soup->render('project/page/updateNew');