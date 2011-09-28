<?php
require_once("../../global.php");

if(Session::isLoggedIn())
	Session::signOut();

// get email, if exists
$email = @Filter::email($_GET['email']);

$soup = new Soup();
if(!empty($email)) {
	$soup->set('email', $email);
}

$soup->render('site/page/consent_adult');