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

$events = Event::getReflectionsEventsByProjectID($project->getID(), 5);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('events', $events);

// if(Session::isLoggedIn()) {
	// $moreDiscussions = Discussion::getMoreDiscussions(Session::getUserID(), $project->getID());
	// $soup->set('moreDiscussions',$moreDiscussions);
	// $yourDiscussions = Discussion::getByUserID(Session::getUserID(), $project->getID());
	// $soup->set('yourDiscussions', $yourDiscussions);
// } else {
	$reflections = Discussion::getReflectionsByProjectID($project->getID());
	$soup->set('reflections',$reflections);
// }

$soup->render('project/page/reflections');