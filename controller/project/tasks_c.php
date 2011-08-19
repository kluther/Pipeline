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

$events = Event::getTasksEventsByProjectID($project->getID(), 10);
$discussions = Discussion::getTasksDiscussionsByProjectID($project->getID(), 5);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('events', $events);
$soup->set('discussions', $discussions);

if(Session::isLoggedIn()) {
	$yourTasks = Task::getYourTasks(Session::getUserID(), $project->getID());
	$soup->set('yourTasks', $yourTasks);	
	$moreTasks = Task::getMoreTasks(Session::getUserID(), $project->getID());
	$soup->set('moreTasks', $moreTasks);
	} else {
	$tasks = Task::getByProjectID($project->getID());
	$soup->set('tasks', $tasks);
	}

$soup->render('project/page/tasks');