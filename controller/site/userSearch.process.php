<?php
require_once('./../../global.php');

$relationship = Filter::text($_GET['relationship']);
$term = Filter::text($_GET['term']);

if($relationship == 'not-me') {
	$usernames = User::getAllUsernames($term, Session::getUserID());
}
echo json_encode($usernames);