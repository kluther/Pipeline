<?php
require_once("../../global.php");

if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();	
}

$messages = Message::getReceivedMessagesByUserID(Session::getUserID());

$soup = new Soup();

$soup->set('messages', $messages);

$soup->render('site/page/inbox');

