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
$events = Event::getAllEvents(50);


$soup = new Soup();

$soup->set('projects', $projects);
$soup->set('users', $users);
$soup->set('events', $events);

$soup->render('site/page/admin');