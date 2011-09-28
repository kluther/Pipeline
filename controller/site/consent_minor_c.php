<?php
require_once("../../global.php");

if(Session::isLoggedIn())
	Session::signOut();

$soup = new Soup();
$soup->render('site/page/consent_minor');