<?php
require_once("../../global.php");

if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();	
}

// get message
$messageID = Filter::numeric($_GET['m']);
$message = Message::load($messageID);
if(empty($message)) {
	header('Location: '.Url::error());
	exit();
}

// if this is a reply, get the parent message
if($message->getID() != $message->getParentID()) {
	$message = Message::load($message->getParentID());
}
$message->markAllRead(); // we're reading it now

$soup = new Soup();

$soup->set('message', $message);

$soup->render('site/page/message');

