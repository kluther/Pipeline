<?php
require_once("../../global.php");

$soup = new Soup();

$referer = $_SERVER['HTTP_REFERER'];

// redirect if already logged in
if(Session::isLoggedIn())
	header('Location: '.Url::base());
else {
	$soup->set('referer', $referer);
	$soup->render('site/page/login');
}