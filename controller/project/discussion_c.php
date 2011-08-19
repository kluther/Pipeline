<?php
require_once("../../global.php");

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

$discussionID = Filter::numeric($_GET['d']);
$discussion = Discussion::load($discussionID);

// kick us out if slug invalid
if($project == null)
{
	header('Location: '.Url::error());
	exit();
}

$replies = $discussion->getReplies("ASC");
$events = Event::getDiscussionEvents($discussionID, 10);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('discussion',$discussion);
$soup->set('replies', $replies);
$soup->set('events', $events);
$soup->render('project/page/discussion');