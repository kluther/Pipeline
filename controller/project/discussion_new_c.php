<?php
require_once("../../global.php");

// must be logged in to post discussion
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

// get category, if exists
$c = (isset($_GET['cat'])) ? Filter::text($_GET['cat']) : null;

switch($c) {
	case 'basics':
		$cat = BASICS_ID;
		break;
	case 'tasks':
		$cat = TASKS_ID;
		break;
	case 'people':
		$cat = PEOPLE_ID;
		break;
	case 'activity':
		$cat = ACTIVITY_ID;
		break;
	default:
		$cat = null;
}

// get your discussions
$yourDiscussions = Discussion::getByUserID(Session::getUserID(), $project->getID());

$soup = new Soup();
$soup->set('project', $project);
$soup->set('cat', $cat);
$soup->set('yourDiscussions', $yourDiscussions);
$soup->render('project/page/discussionNew');