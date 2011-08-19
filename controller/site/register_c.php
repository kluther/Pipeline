<?php

require_once("../../global.php");

if(Session::isLoggedIn())
	header('Location: '.Url::base()); // don't let logged-in users register!

$soup = new Soup();
$soup->render('site/page/register');
