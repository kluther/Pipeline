<?php
require_once("../../global.php");

if(!Session::isLoggedIn()) {
	header('Location: '.Url::error());
	exit();
}

// get all themes
$themes = Theme::getThemes();

// get ID of user's theme
$u = Session::getUser();
$userThemeID = ($u->getThemeID() != null) ? $u->getThemeID() : DEFAULT_THEME_ID;
unset($u);

$soup = new Soup();

$soup->set('themes', $themes);
$soup->set('userThemeID', $userThemeID);

$soup->render('site/page/settings');