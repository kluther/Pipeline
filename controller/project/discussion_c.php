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

// page number, if any
if(empty($_GET['page']))
	$page = 1;
else
	$page = Filter::numeric($_GET['page']);

$discussionID = Filter::numeric($_GET['d']);
$discussion = Discussion::load($discussionID);

define('REPLIES_PER_PAGE', 10); // how many replies per page
$totalNumReplies = count($discussion->getReplies()); // total # replies
$numPages = ceil($totalNumReplies/REPLIES_PER_PAGE); // get # pages
if( ($numPages != 0) && ($page > $numPages) ) {
	// invalid page number
	header('Location: '.Url::error());
	exit();
}

$limit = ($page-1)*REPLIES_PER_PAGE.', '.REPLIES_PER_PAGE;
$replies = $discussion->getReplies("ASC", $limit); // get replies

$events = Event::getDiscussionEvents($discussionID, 10);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('discussion',$discussion);
$soup->set('replies', $replies);
$soup->set('events', $events);
$soup->set('page', $page);
$soup->set('numPages', $numPages);
$soup->render('project/page/discussion');