<?php
require_once("../../global.php");

$user = User::load(Session::getUserID());
$action = Filter::text($_POST['action']);

if($action == 'theme') {
	// get the new theme
	$themeID = Filter::numeric($_POST['themeID']);
	$theme = Theme::load($themeID);
	
	// validate the theme
	if(empty($theme)) {
		$json = array( 'error' => 'That theme does not exist.' );
		exit(json_encode($json));
	}
	
	// save the new theme
	$user->setThemeID($theme->getID());
	$user->save();
	
	// send us back
	Session::setMessage("Theme changed.");
	$json = array('success' => '1');
	echo json_encode($json);
	
} elseif($action == 'notification') {
	$notificationType = Filter::alphanum($_POST['notificationType']);
	$notificationValue = Filter::alphanum($_POST['notificationValue']);
	// convert checkbox value to database-friendly 1 or 0
	$value = ($notificationValue == 'notify') ? 1 : 0;
	
	// figure out which User setter to use based on notification type
	switch($notificationType) {
		case 'chkCommentTaskLeading':
			$user->setNotifyCommentTaskLeading($value);
			break;
		case 'chkEditTaskJoined':
			$user->setNotifyEditTaskAccepted($value);
			break;
		case 'chkCommentTaskJoined':
			$user->setNotifyCommentTaskAccepted($value);
			break;
		case 'chkCommentTaskUpdate':
			$user->setNotifyCommentTaskUpdate($value);
			break;
		case 'chkInviteProject':
			$user->setNotifyInviteProject($value);
			break;			
		case 'chkTrustProject':
			$user->setNotifyTrustProject($value);
			break;
		case 'chkBannedProject':
			$user->setNotifyBannedProject($value);
			break;
		case 'chkDiscussionStarted':
			$user->setNotifyDiscussionStarted($value);
			break;			
		case 'chkDiscussionReply':
			$user->setNotifyDiscussionReply($value);
			break;
		case 'chkMakeTaskLeader':
			$user->setNotifyMakeTaskLeader($value);
			break;
		case 'chkReceiveMessage':
			$user->setNotifyReceiveMessage($value);
			break;
		case 'chkMassEmail':
			$user->setNotifyMassEmail($value);
			break;
		default:
			$json = array( 'error' => 'Invalid notification type.' );
			exit(json_encode($json));
	}
	$user->save(); // save changes
	Session::setMessage("Notification settings changed.");
	$json = array('success' => '1');
	echo json_encode($json);	
} else {
	$json = array( 'error' => 'Invalid action.' );
	exit(json_encode($json));
}