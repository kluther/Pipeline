<?php
require_once("../../global.php");

if(!Session::isAdmin()) {
	header('Location: '.Url::error());
	exit();
}

$types = DocTypes::getAllTypes();

$soup = new Soup();

$soup->set('selected','settings');
$soup->set('types',$types);
$soup->render('site/page/adminSettings');
