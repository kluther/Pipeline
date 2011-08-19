<?php
require_once("../../global.php");

$soup = new Soup();

// redirect if already logged in
if(Session::isLoggedIn())
	header('Location: '.Url::base());
else
	$soup->render('site/page/login');