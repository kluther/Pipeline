<?php
require_once("../../global.php");

$soup = new Soup();

if(Session::isLoggedIn()) {	
	// dashboard
	$yourProjects = Project::getByUserID(Session::getUserID());
	$publicProjects = Project::getPublicProjects(10); // projects to join
	//$user = User::load(Session::getUserID());
	$events = Event::getDashboardEvents(Session::getUserID(), 10);
	// $updates = Update::getByUserID($user->getID());
	// $discussions = Discussion::getByUserID($user->getID());
	$invitations = Invitation::getByUserID(Session::getUserID());
	$unrespondedInvites = Invitation::getByUserID(Session::getUserID(), null, false);
	$yourTasks = Task::getYourTasks(Session::getUserID());
	
	$soup->set('yourProjects', $yourProjects);
	$soup->set('publicProjects', $publicProjects);
	//$soup->set('user', $user);
	$soup->set('events', $events);
	// $soup->set('updates', $updates);
	// $soup->set('discussions', $discussions);
	$soup->set('invitations', $invitations);
	$soup->set('unrespondedInvites', $unrespondedInvites);
	$soup->set('tasks', $yourTasks);
	$soup->render('site/page/dashboard');
} else {	
	// home page
	$events = Event::getHomeEvents(10);
	$soup->set('events', $events);
	$soup->render('site/page/home');
}
