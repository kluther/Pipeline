<?php
require_once("../../global.php");

$soup = new Soup();

$projects = Project::getPublicProjects();

$soup->set('projects', $projects);
$soup->render('site/page/find');
