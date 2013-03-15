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

$events = Event::getTasksEventsByProjectID($project->getID(), 5);
$discussions = Discussion::getTasksDiscussionsByProjectID($project->getID(), 5);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('events', $events);
$soup->set('discussions', $discussions);

if(Session::isLoggedIn()) {
        $projectId = $project->getID();
	$yourTasks = Task::getYourTasks(Session::getUserID(), $projectId);
	$soup->set('yourTasks', $yourTasks);
        $unclaimedTasks = Task::getUnclaimedTasks(Session::getUserID(), $projectId,null,true);
        $soup->set('unclaimedTasks',$unclaimedTasks);
	$moreTasks = Task::getMoreTasks(Session::getUserID(), $projectId, null, true);
	$moreTasksFiltered = array();
        foreach ($moreTasks as $task){
            if ($task->getNumAccepted() > 0){
                array_push($moreTasksFiltered,$task);
            }
        }
        $soup->set('moreTasks', $moreTasksFiltered);
        $closedTasks = Task::getClosedTasks($projectId);
        $soup->set('closedTasks',$closedTasks);
} else {
	$tasks = Task::getByProjectID($project->getID());
	$soup->set('tasks', $tasks);
}

$soup->render('project/page/tasks');