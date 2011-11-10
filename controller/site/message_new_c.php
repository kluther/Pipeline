<?php
require_once("../../global.php");

if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();	
}
$soup = new Soup();


if(isset($_GET['un'])) {
	$username = Filter::text($_GET['un']);
	$soup->set('username', $username);
}

$soup->render('site/page/messageNew');

