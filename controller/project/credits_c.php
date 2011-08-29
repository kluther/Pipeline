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

$creatorID = $project->getCreatorID();
$creator = User::load($creatorID);

$organizers = $project->getOrganizers();
$contributors = $project->getContributors();
$followers = $project->getFollowers();
$banned = $project->getBanned();

$discussions = Discussion::getPeopleDiscussionsByProjectID($project->getID(), 3);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('creator', $creator);
$soup->set('organizers', $organizers);
$soup->set('contributors', $contributors);
$soup->set('banned', $banned);
$soup->set('followers', $followers);

$soup->set('discussions', $discussions);

$soup->render('project/page/credits');