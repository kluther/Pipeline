<?php
require_once("../../global.php");

$soup = new Soup();

$projects = Project::getLookingForHelp();

$soup->set('projects', $projects);
$soup->render('site/page/find');
