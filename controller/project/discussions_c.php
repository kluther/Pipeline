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

$events = Event::getDiscussionsEventsByProjectID($project->getID(), 5);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('events', $events);

// if(Session::isLoggedIn()) {
	// $moreDiscussions = Discussion::getMoreDiscussions(Session::getUserID(), $project->getID());
	// $soup->set('moreDiscussions',$moreDiscussions);
	// $yourDiscussions = Discussion::getByUserID(Session::getUserID(), $project->getID());
	// $soup->set('yourDiscussions', $yourDiscussions);
// } else {
	$discussions = Discussion::getByProjectID($project->getID());
	$soup->set('discussions',$discussions);
// }

$soup->render('project/page/discussions');