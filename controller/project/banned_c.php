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

$banned = ProjectUser::getBanned($project->getID());

$soup = new Soup();
$soup->set('project', $project);
$soup->set('banned', $banned);
$soup->render('project/page/banned');