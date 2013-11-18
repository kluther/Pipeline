<?php
require_once("../../global.php");
require_once TEMPLATE_PATH.'/site/helper/format.php';

//Find referral url in case there is a problem and we have to redirect the user
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Url::dashboard();

    /*if (empty($fileName)) {
        Session::setMessage('You must select a CSV file');
        header('Location: '.$referer);
        exit();
    }*/

//Check if project creator or admin
if (Session::isAdmin()) {
    
}
//User should not hit this statement because we should only be showing the upload form
// if the user is an admin.
else {
    header('Location: '.Url::error());
    exit();
}
