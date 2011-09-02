<?php
require_once("../../global.php");

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

// kick us out if slug invalid or not organizer/creator

if($project == null) {
	header('Location: '.Url::error());
	exit();
} elseif( (!ProjectUser::isOrganizer(Session::getUserID(), $project->getID())) &&
	(!ProjectUser::isCreator(Session::getUserID(), $project->getID())) ) {
	header('Location: '.Url::error());
	exit();	
}

$soup = new Soup();
$soup->set('project', $project);
$soup->render('project/page/taskNew');