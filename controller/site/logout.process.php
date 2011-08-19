<?php
require_once('./../../global.php');
Session::signOut();
header('Location: '.Url::base());