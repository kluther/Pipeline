<?php
require_once('./../../global.php');
$referer = $_SERVER['HTTP_REFERER'];
Session::signOut();
header('Location: '.Url::base());