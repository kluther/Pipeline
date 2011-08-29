<?php
require_once("../../global.php");

$userName = Filter::text($_GET['un']);
$user = User::loadByUsername($userName);

// make sure user exists
if($user === null) {
	header('Location: '.Url::error());
	exit();
}

$events = Event::getUserEvents($user->getID(), 10);

$soup = new Soup();
$soup->set('user', $user);
$soup->set('events', $events);
$soup->render('site/page/user');
