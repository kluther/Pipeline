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

//$r is the reflection
//$cu is the current user
function permissionCheck($r, $cu)
{
	$vis = $r->getReflectionVisibility();
	if($vis == Discussion::REFLECT_VIS_ME)
	{
		if($r->getCreatorID() == $cu)
		{
			return true;
		}
	}
	elseif($vis == Discussion::REFLECT_VIS_ME_INSTR)
	{
		if($r->getCreatorID() == $cu || Session::isInstructor() == true)
		{
			return true;
		}
	}
	elseif($vis == Discussion::REFLECT_VIS_ME_INSTR_PROJ_MEMB)
	{
		$p = $r->getProjectID();
		$c = Session::getUserID();
		$t = ProjectUser::isMember($c, $p);
		if($r->getCreatorID() == $cu || Session::isInstructor() == true || $t == true)
		{
			return true;
		}
	}
	elseif($vis == Discussion::REFLECT_VIS_EVERYONE)
	{
			return true;
	}
	else
	{
		return false;
	}
}