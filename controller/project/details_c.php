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

$events = Event::getBasicsEventsByProjectID($project->getID(), 5);
$discussions = Discussion::getBasicsDiscussionsByProjectID($project->getID(), 5);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('events', $events);
$soup->set('discussions', $discussions);
$soup->render('project/page/details');