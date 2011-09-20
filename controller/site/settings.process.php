<?php
require_once("../../global.php");

$user = User::load(Session::getUserID());
$action = Filter::text($_POST['action']);

if($action == 'notification') {
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
		case 'chkOrganizeProject':
			$user->setNotifyOrganizeProject($value);
			break;
		case 'chkContributeProject':
			$user->setNotifyContributeProject($value);
			break;			
		case 'chkFollowProject':
			$user->setNotifyFollowProject($value);
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