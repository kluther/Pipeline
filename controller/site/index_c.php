<?php
require_once("../../global.php");

$soup = new Soup();

if(Session::isLoggedIn())
{	
	$soup->render('site/page/dashboard');
}
else
{	
	$soup->render('site/page/home');
}
