<?php
require_once("../../global.php");

if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();
}

$soup = new Soup();

$soup->render('site/page/settings');