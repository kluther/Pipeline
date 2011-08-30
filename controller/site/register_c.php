<?php

require_once("../../global.php");

if(Session::isLoggedIn())
	header('Location: '.Url::base()); // don't let logged-in users register!

// get registration code (e.g. from email invite), if exists
$code = @Filter::alphanum($_GET['code']);

$soup = new Soup();

// if code exists, get related invitation
if($code != null) {
	$invite = Invitation::findByCode($code);
	if($invite != null) {
		$soup->set('invite', $invite);
	}
}

$soup->render('site/page/register');
