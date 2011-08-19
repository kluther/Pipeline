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

$discussions = Discussion::getByProjectID($project->getID());
$events = Event::getDiscussionsEventsByProjectID($project->getID(), 10);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('discussions',$discussions);
$soup->set('events', $events);
$soup->render('project/page/discussions');