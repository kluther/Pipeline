<?php
require_once("../../global.php");

$inviteID = Filter::numeric($_POST['inviteID']);
$invite = Invitation::load($inviteID);

if( ProjectUser::isMember($invite->getInviteeID(), $invite->getProjectID()) ||
	ProjectUser::isTrusted($invite->getInviteeID(), $invite->getProjectID())	) {
	// already a member; can't join
	Session::setMessage('You are already a member of this project.');
	header('Location: '.Url::error());
	exit();
}

$response = Filter::alphanum($_POST['response']);
if($response == 'accept') {
	$response = Invitation::ACCEPTED;
} else {
	$response = Invitation::DECLINED;
}

if($response == Invitation::ACCEPTED) {
	// add the user to the project
	if($invite->getTrusted) {
		$relationship = ProjectUser::TRUSTED;
	} else {
		$relationship = ProjectUser::MEMBER;
	}
	$pu = new ProjectUser(array(
		'project_id' => $invite->getProjectID(),
		'user_id' => $invite->getInviteeID(),
		'relationship' => $relationship
	));
	$pu->save();
	
	$eventTypeID = 'accept_member_invitation';
	$successMsg = 'You accepted the invitation.';	
} else {
	$eventTypeID = 'decline_member_invitation';
	$successMsg = 'You declined the invitation.';
}

// update the invite
$invite->setResponse($response);
$invite->setDateResponded(date("Y-m-d H:i:s"));
$invite->save();

// log the event
$logEvent = new Event(array(
	'event_type_id' => $eventTypeID,
	'user_1_id' => $invite->getInviteeID(),
	'user_2_id' => $invite->getInviterID(),
	'project_id' => $invite->getProjectID(),
	'item_1_id' => $invite->getID()
));
$logEvent->save();

// set confirm message and send us away
Session::setMessage($successMsg);
$json = array( 'success' => '1');
echo json_encode($json);

?>