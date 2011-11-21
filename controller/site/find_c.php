<?php
require_once("../../global.php");

$soup = new Soup();

if(Session::isLoggedIn()) {
	$projects = Project::getPublicProjects(Session::getUserID());
} else {
	$projects = Project::getPublicProjects();
}

$soup->set('projects', $projects);
$soup->render('site/page/find');
