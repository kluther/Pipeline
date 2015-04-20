<?php
require_once("../../global.php");

// must be logged in to post reflection
if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();
}

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

// kick us out if slug invalid
if($project == null)
{
	header('Location: '.Url::error());
	exit();
}

// if private project, limit access to invited users, members, and admins
// and exclude banned members
if($project->getPrivate()) {
	if (!Session::isAdmin() && (!$project->isCreator(Session::getUserID()))) {
		if (((!$project->isInvited(Session::getUserID())) && (!$project->isMember(Session::getUserID())) &&
		(!$project->isTrusted(Session::getUserID()))) || ProjectUser::isBanned(Session::getUserID(),$project->getID())) {
		 	header('Location: '.Url::error());
			exit();		
		}
	}
}

// get your reflections
$yourReflections = Discussion::getReflectionsByUserID(Session::getUserID(), $project->getID());

$soup = new Soup();
$soup->set('project', $project);
$soup->set('yourReflections', $yourReflections);
$soup->render('project/page/reflectionNew');