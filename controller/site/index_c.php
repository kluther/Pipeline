<?php
require_once("../../global.php");

$soup = new Soup();

if(Session::isLoggedIn())
{	
	$projects = Project::getByUserID(Session::getUserID());
	$user = User::load(Session::getUserID());
	$events = Event::getDashboardEvents($user->getID(), 10);
	$invitations = array();
	$yourTasks = Task::getYourTasks($user->getID());
	
	$soup->set('projects', $projects);
	$soup->set('user', $user);
	$soup->set('events', $events);
	$soup->set('invitations', $invitations);
	$soup->set('tasks', $yourTasks);
	$soup->render('site/page/dashboard');
}
else
{	
	$soup->render('site/page/home');
}
