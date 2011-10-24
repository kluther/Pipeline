<?php
require_once("../../global.php");

$userName = Filter::text($_GET['un']);
$user = User::loadByUsername($userName);

// make sure user exists
if($user === null) {
	header('Location: '.Url::error());
	exit();
}

$events = Event::getUserEvents($user->getID(), false, 10);
$tasks = Task::getByUserID($user->getID(), null, false);
$projects = Project::getByUserID($user->getID(), false);

$soup = new Soup();
$soup->set('user', $user);
$soup->set('events', $events);
$soup->set('tasks', $tasks);
$soup->set('projects', $projects);
$soup->render('site/page/user');
