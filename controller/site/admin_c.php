<?php
require_once("../../global.php");

if(!Session::isAdmin()) {
	header('Location: '.Url::error());
	exit();
}

// projects
$projects = Project::getAllProjects();

// users
$users = User::getAllUsers();

// activity


// page number, if any
if(empty($_GET['page']))
	$page = 1;
else
	$page = Filter::numeric($_GET['page']);

define('EVENTS_PER_PAGE', 15); // how many events per page

$totalNumEvents = count(Event::getAllEvents());

$numPages = ceil($totalNumEvents/EVENTS_PER_PAGE); // get # pages
if( ($numPages != 0) && ($page > $numPages) ) {
	// invalid page number
	header('Location: '.Url::error());
	exit();
}

// figure out which events to get
$limit = ($page-1)*EVENTS_PER_PAGE.', '.EVENTS_PER_PAGE;

$events = Event::getAllEvents($limit);


$soup = new Soup();

$soup->set('projects', $projects);
$soup->set('users', $users);
$soup->set('events', $events);
$soup->set('page', $page);
$soup->set('numPages', $numPages);
$soup->render('site/page/admin');




